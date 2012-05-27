<?php
// All in one Php Feed Writer
//RSS 1.0 ,RSS 2.0 , ATOM
/*
Author :: Ramandeep Singh
Created:: Feb/08/2012
Website:: http://designaeon.com
*/
//constants
	define('RSS1', 'RSS 1.0', true);
	define('RSS2', 'RSS 2.0', true);
	define('ATOM', 'ATOM', true);
//constants
        
 class FeedItem
 {
	private $elements = array();    //Collection of feed elements
	private $version;	
	function __construct($version = RSS2)
	{    
		$this->version = $version;
	}
	public function addElement($elementName, $content, $attributes = null)
	{
		$this->elements[$elementName]['name']       = $elementName;
		$this->elements[$elementName]['content']    = $content;
		$this->elements[$elementName]['attributes'] = $attributes;
	}	
	public function addElements($elementArray)
	{
		if(! is_array($elementArray)) return;
		foreach ($elementArray as $elementName => $content) 
		{
			$this->addElement($elementName, $content);
		}
	}	
	public function getElements()
	{
		return $this->elements;
	}	
	public function setItemDescription($description) 
	{
		$tag = ($this->version == ATOM)? 'summary' : 'description'; 
		$this->addElement($tag, $description);
	}	
	public function setItemTitle($title) 
	{
		$this->addElement('title', $title);  	
	}	
	public function setDate($date) 
	{
		if(! is_numeric($date))
		{
			$date = strtotime($date);
		}
		
		if($this->version == ATOM)
		{
			$tag    = 'updated';
			$value  = date(DATE_ATOM, $date);
		}        
		elseif($this->version == RSS2) 
		{
			$tag    = 'pubDate';
			$value  = date(DATE_RSS, $date);
		}
		else                                
		{
			$tag    = 'dc:date';
			$value  = date("Y-m-d", $date);
		}
		
		$this->addElement($tag, $value);    
	}	
	public function setItemLink($link) 
	{
		if($this->version == RSS2 || $this->version == RSS1)
		{
			$this->addElement('link', $link);
		}
		else
		{
			$this->addElement('link','',array('href'=>$link));
			$this->addElement('id', FeedWriter::uuid($link,'urn:uuid:'));
		} 
		
	}	
 } // end of Items
 class FeedWriter extends FeedItem
 {
	 private $channels      = array();  // Collection of channel elements
	 private $items         = array();  // Collection of items as object of FeedItem class.
	 private $data          = array();  // holds some other version specific data
	 private $CDATAEncoding = array();  // The tag names which have to encoded as CDATA
	 
	 private $version   = null; 
         public $feeditem;
	
	function __construct($version = RSS2)//by default version is rss2
	{	
                parent::__construct($version);
		$this->version = $version;
		$this->feeditem=new FeedItem($version);	
		// Setting default value for essential channel elements
		$this->channels['title']        = $version . ' Feed';
		$this->channels['link']         = 'http://www.designaeon.com';
				
		//Tag names to encode in CDATA
		$this->CDATAEncoding = array('description', 'content:encoded', 'summary');               
	}	
	public function setChannelElement($elementName, $content)
	{
		$this->channels[$elementName] = $content ;
	}	
	public function setChannelElements($elementArray)
	{
		if(! is_array($elementArray)) return;
		foreach ($elementArray as $elementName => $content) 
		{
			$this->setChannelElement($elementName, $content);
		}
	}	
	public function burnFeed()
	{
		header("Content-type: text/xml");
		
		$this->printTop();
		$this->printChannels();
		$this->printItems();
		$this->printBottom();
	}	
	public function insertItem($feedItem)
	{
		$this->items[] = $feedItem;
                $Item = new FeedItem($this->version);
		return $Item;
	}	
	public function setImage($title, $link, $url)
	{
		$this->setChannelElement('image', array('title'=>$title, 'link'=>$link, 'url'=>$url));
	}	
	public function setRss1About($url)
	{
		$this->data['ChannelAbout'] = $url;    
	}  
        public static function uuid($key = null, $prefix = '') //uuid for atom feeds
          {
                $key = ($key == null)? uniqid(rand()) : $key;
                $chars = md5($key);
                $uuid  = substr($chars,0,8) . '-';
                $uuid .= substr($chars,8,4) . '-';
                $uuid .= substr($chars,12,4) . '-';
                $uuid .= substr($chars,16,4) . '-';
                $uuid .= substr($chars,20,12);

                return $prefix . $uuid;
          }	
	private function printTop()
	{
		$out  = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		
		if($this->version == RSS2)
		{
			$out .= '<rss version="2.0"
					xmlns:content="http://purl.org/rss/1.0/modules/content/"
					xmlns:wfw="http://wellformedweb.org/CommentAPI/"
				  >' . PHP_EOL;
		}    
		elseif($this->version == RSS1)
		{
			$out .= '<rdf:RDF 
					 xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
					 xmlns="http://purl.org/rss/1.0/"
					 xmlns:dc="http://purl.org/dc/elements/1.1/"
					>' . PHP_EOL;;
		}
		else if($this->version == ATOM)
		{
			$out .= '<feed xmlns="http://www.w3.org/2005/Atom">' . PHP_EOL;;
		}
		echo $out;
	}	
	private function printBottom()
	{
		if($this->version == RSS2)
		{
			echo '</channel>' . PHP_EOL . '</rss>'; 
		}    
		elseif($this->version == RSS1)
		{
			echo '</rdf:RDF>';
		}
		else if($this->version == ATOM)
		{
			echo '</feed>';
		}
	  
	}
	private function convertToXml($tagName, $tagContent, $attributes = null)
	{       
		$nodeText = '';
		$attrText = '';

		if(is_array($attributes))
		{
			foreach ($attributes as $key => $value) 
			{
				$attrText .= " $key=\"$value\" ";
			}
		}
		
		if(is_array($tagContent) && $this->version == RSS1)
		{
			$attrText = ' rdf:parseType="Resource"';
		}		
		
		$attrText .= (in_array($tagName, $this->CDATAEncoding) && $this->version == ATOM)? ' type="html" ' : '';
		$nodeText .= (in_array($tagName, $this->CDATAEncoding))? "<{$tagName}{$attrText}><![CDATA[" : "<{$tagName}{$attrText}>";
		 
		if(is_array($tagContent))
		{ 
			foreach ($tagContent as $key => $value) 
			{
				$nodeText .= $this->convertToXml($key, $value);
			}
		}
		else
		{
			$nodeText .= (in_array($tagName, $this->CDATAEncoding))? $tagContent : htmlentities($tagContent);
		}           
			
		$nodeText .= (in_array($tagName, $this->CDATAEncoding))? "]]></$tagName>" : "</$tagName>";

		return $nodeText . PHP_EOL;
	}	
	private function printChannels()
	{
		//Start channel tag
		switch ($this->version) 
		{
		   case RSS2: 
				echo '<channel>' . PHP_EOL;        
				break;
		   case RSS1: 
				echo (isset($this->data['ChannelAbout']))? "<channel rdf:about=\"{$this->data['ChannelAbout']}\">" : "<channel rdf:about=\"{$this->channels['link']}\">";
				break;
		}
		
		//Print Items of channel
		foreach ($this->channels as $key => $value) 
		{
			if($this->version == ATOM && $key == 'link') 
			{
				// ATOM prints link element as href attribute
				echo $this->convertToXml($key,'',array('href'=>$value));
				//Add the id for ATOM
				echo $this->convertToXml('id',$this->uuid($value,'urn:uuid:'));
			}
			else
			{
				echo $this->convertToXml($key, $value);
			}    
			
		}
		
		//RSS 1.0 have special tag <rdf:Seq> with channel 
		if($this->version == RSS1)
		{
			echo "<items>" . PHP_EOL . "<rdf:Seq>" . PHP_EOL;
			foreach ($this->items as $item) 
			{
				$thisItems = $item->getElements();
				echo "<rdf:li resource=\"{$thisItems['link']['content']}\"/>" . PHP_EOL;
			}
			echo "</rdf:Seq>" . PHP_EOL . "</items>" . PHP_EOL . "</channel>" . PHP_EOL;
		}
	}	
	private function printItems()
	{    
		foreach ($this->items as $item) 
		{
			$thisItems = $item->getElements();
			
			//the argument is printed as rdf:about attribute of item in rss 1.0 
			echo $this->startItem($thisItems['link']['content']);
			
			foreach ($thisItems as $feedItem ) 
			{
				echo $this->convertToXml($feedItem['name'], $feedItem['content'], $feedItem['attributes']); 
			}
			echo $this->endItem();
		}
	}
	
	private function startItem($about = false)
	{
		if($this->version == RSS2)
		{
			echo '<item>' . PHP_EOL; 
		}    
		elseif($this->version == RSS1)
		{
			if($about)
			{
				echo "<item rdf:about=\"$about\">" . PHP_EOL;
			}
			else
			{
				die('link element is not set .\n It\'s required for RSS 1.0 to be used as about attribute of item');
			}
		}
		else if($this->version == ATOM)
		{
			echo "<entry>" . PHP_EOL;
		}    
	}
	private function endItem()
	{
		if($this->version == RSS2 || $this->version == RSS1)
		{
			echo '</item>' . PHP_EOL; 
		}    
		else if($this->version == ATOM)
		{
			echo "</entry>" . PHP_EOL;
		}
	}	
 } 
?>