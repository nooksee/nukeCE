<?php

/**************************************************************************/
/* PHP-Nuke CE: Web Portal System                                         */
/* ==============================                                         */
/*                                                                        */
/* Copyright (c) 2011 by Kevin Atwood                                     */
/* http://www.nukece.com                                                  */
/*                                                                        */
/* All PHP-Nuke CE code is released under the GNU General Public License. */
/* See CREDITS.txt, COPYRIGHT.txt and LICENSE.txt.                        */
/**************************************************************************/

echo '
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html>
          <head>
              <title>
                  Extended Status Help
              </title>
              <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
              <style type="text/css">
                  h1.myclass {font-size: 20pt; font-weight: bold; color: blue; text-align: center}
                  h1.myclass2 {font-size: 11pt; font-style: normal; text-align: left}
              </style>
          </head>
          <body>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass">
                              Extended Status Help
                          </h1>
                      </td>
                  </tr>
              </table>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              The server maintains many status variables that provide information 
                              about its operation. 
		          </h1>
                      </td>
                  </tr>
              </table>
              <table border="0" width="100%">
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              The status variables have the following meanings.
                          </h1>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Aborted_clients
                          </h1>
		          The number of connections that were aborted because the client died 
                          without closing the connection properly.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Aborted_connects
                          </h1>
                          The number of failed attempts to connect to the MySQL server.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Binlog_cache_disk_use
                          </h1>
                          The number of transactions that used the temporary binary log cache but 
                          that exceeded the value of binlog_cache_size and used a temporary file to 
                          store statements from the transaction.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Binlog_cache_use
                          </h1>
                          The number of transactions that used the temporary binary log cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Bytes_received
                          </h1>
                          The number of bytes received from all clients.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Bytes_sent
                          </h1>
                          The number of bytes sent to all clients.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Com_xxx
                          </h1>
                          The Com_xxx statement counter variables indicate the number of times each 
                          xxx statement has been executed. There is one status variable for each type 
                          of statement. For example, Com_delete and Com_insert count DELETE and INSERT statements, 
                          respectively. However, if a query result is returned from query cache, the server 
                          increments the Qcache_hits status variable, not Com_select.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Compression
                          </h1>
                          Whether the client connection uses compression in the client/server protocol. 
                          Added in MySQL 5.1.2.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Connections
                          </h1>
                          The number of connection attempts (successful or not) to the MySQL server.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Created_tmp_disk_tables
                          </h1>
                          The number of internal on-disk temporary tables created by the server while executing 
                          statements.If an internal temporary table is created initially as an in-memory table 
                          but becomes too large, MySQL automatically converts it to an on-disk table.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Created_tmp_files
                          </h1>
                          How many temporary files mysqld has created.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Created_tmp_tables
                          </h1>
                          The number of internal temporary tables created by the server while executing statements. 
                          Each invocation of the SHOW STATUS statement uses an internal temporary table and increments 
                          the global Created_tmp_tables value.        
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Delayed_errors
                          </h1>
                          The number of rows written with INSERT DELAYED for which some error occurred 
                          (probably duplicate key).
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Delayed_insert_threads
                          </h1>
                          The number of INSERT DELAYED handler threads in use.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Delayed_writes
                          </h1>
                          The number of INSERT DELAYED rows written.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Flush_commands
                          </h1>
                          The number of executed FLUSH statements.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_commit
                          </h1>
                          The number of internal COMMIT statements.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_delete
                          </h1>
                          The number of times that rows have been deleted from tables.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_prepare
                          </h1>
                          A counter for the prepare phase of two-phase commit operations.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_read_first
                          </h1>
                          The number of times the first entry in an index was read. If this value is high, 
                          it suggests that the server is doing a lot of full index scans; for example, 
                          SELECT col1 FROM foo, assuming that col1 is indexed.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_read_key
                          </h1>
                          The number of requests to read a row based on a key. If this value is high, 
                          it is a good indication that your tables are properly indexed for your queries.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_read_next
                          </h1>
                          The number of requests to read the next row in key order. This value is 
                          incremented if you are querying an index column with a range constraint or if 
                          you are doing an index scan.
                      </td>
                  </tr>
                  <tr>
                      <td>
                         <h1 class="myclass2">
                             Handler_read_prev
                         </h1>
                         The number of requests to read the previous row in key order. This read method 
                         is mainly used to optimize ORDER BY ... DESC.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_read_rnd
                          </h1>
                          The number of requests to read a row based on a fixed position. This value is 
                          high if you are doing a lot of queries that require sorting of the result. You 
                          probably have a lot of queries that require MySQL to scan entire tables or you 
                          have joins that do not use keys properly.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_read_rnd_next
                          </h1>
                          The number of requests to read the next row in the data file. This value is high 
                          if you are doing a lot of table scans. Generally this suggests that your tables 
                          are not properly indexed or that your queries are not written to take advantage of 
                          the indexes you have.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_rollback
                          </h1>
                          The number of requests for a storage engine to perform a rollback operation.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_savepoint
                          </h1>
                          The number of requests for a storage engine to place a savepoint.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_savepoint_rollback
                          </h1>
                          The number of requests for a storage engine to roll back to a savepoint.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_update
                          </h1>
                          The number of requests to update a row in a table.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Handler_write
                          </h1>
                          The number of requests to insert a row in a table.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_pages_data
                          </h1>
                          The number of pages containing data (dirty or clean).
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_pages_dirty
                          </h1>
                          The number of pages currently dirty.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_pages_flushed
                          </h1>
                          The number of buffer pool page-flush requests.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_pages_free
                          </h1>
                          The number of free pages.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_pages_latched
                          </h1>
                          The number of latched pages in InnoDB buffer pool. These are pages currently 
                          being read or written or that cannot be flushed or removed for some other reason. 
                          Calculation of this variable is expensive, so as of MySQL 5.1.28, it is available 
                          only when the UNIV_DEBUG system is defined at server build time.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_pages_misc
                          </h1>
                          The number of pages that are busy because they have been allocated for 
                          administrative overhead such as row locks or the adaptive hash index.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_pages_total
                          </h1>
                          The total size of the buffer pool, in pages.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_read_ahead
                          </h1>
                          (InnoDB Plugin only) The number of pages read into the InnoDB buffer 
                          pool by the read-ahead background thread.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_read_ahead_evicted
                          </h1>
                          (InnoDB Plugin only) The number of pages read into the InnoDB buffer 
                          pool by the read-ahead background thread that were subsequently evicted 
                          without having been accessed by queries.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_read_ahead_rnd
                          </h1>
                          The number of random read-aheads initiated by InnoDB. This happens when 
                          a query scans a large portion of a table but in random order.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_read_ahead_seq
                          </h1>
                          The number of sequential read-aheads initiated by InnoDB. This happens 
                          when InnoDB does a sequential full table scan.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_read_requests
                          </h1>
                          The number of logical read requests InnoDB has done.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_reads
                          </h1>
                          The number of logical reads that InnoDB could not satisfy from the 
                          buffer pool, and had to read directly from the disk.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_wait_free
                          </h1>
                          Normally, writes to the InnoDB buffer pool happen in the background. 
                          However, if it is necessary to read or create a page and no clean pages are a
                          vailable, it is also necessary to wait for pages to be flushed first. This 
                          counter counts instances of these waits. If the buffer pool size has been set 
                          properly, this value should be small.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_buffer_pool_write_requests
                          </h1>
                          The number writes done to the InnoDB buffer pool.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_fsyncs
                          </h1>
                          The number of fsync() operations so far.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_pending_fsyncs
                          </h1>
                          The current number of pending fsync() operations.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_pending_reads
                          </h1>
                          The current number of pending reads.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_pending_writes
                          </h1>
                          The current number of pending writes.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_read
                          </h1>
                          The amount of data read since the server was started.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_reads
                          </h1>
                          The total number of data reads.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_writes
                          </h1>
                          The total number of data writes.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_data_written
                          </h1>
                          The amount of data written so far, in bytes.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_dblwr_pages_written
                          </h1>
                          The number of pages that have been written for doublewrite operations.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_dblwr_writes
                          </h1>
                          The number of doublewrite operations that have been performed.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_have_atomic_builtins
                          </h1>
                          (InnoDB Plugin only) Indicates whether the server was built with atomic instructions.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_log_waits
                          </h1>
                          The number of times that the log buffer was too small and a wait was required for it 
                          to be flushed before continuing.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_log_write_requests
                          </h1>
                          The number of log write requests.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_log_writes
                          </h1>
                          The number of physical writes to the log file.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_os_log_fsyncs
                          </h1>
                          The number of fsync() writes done to the log file.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_os_log_pending_fsyncs
                          </h1>
                          The number of pending log file fsync() operations.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_os_log_pending_writes
                          </h1>
                          The number of pending log file writes.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_os_log_written
                          </h1>
                          The number of bytes written to the log file.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_page_size
                          </h1>
                          The compiled-in InnoDB page size (default 16KB). Many values are counted in pages; 
                          the page size enables them to be easily converted to bytes.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_pages_created
                          </h1>
                          The number of pages created.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_pages_read
                          </h1>
                          The number of pages read.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_pages_written
                          </h1>
                          The number of pages written.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_row_lock_current_waits
                          </h1>
                          The number of row locks currently being waited for.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_row_lock_time
                          </h1>
                          The total time spent in acquiring row locks, in milliseconds.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_row_lock_time_avg
                          </h1>
                          The average time to acquire a row lock, in milliseconds.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_row_lock_time_max
                          </h1>
                          The maximum time to acquire a row lock, in milliseconds.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_row_lock_waits
                          </h1>
                          The number of times a row lock had to be waited for.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_rows_deleted
                          </h1>
                          The number of rows deleted from InnoDB tables.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_rows_inserted
                          </h1>
                          The number of rows inserted into InnoDB tables.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_rows_read
                          </h1>
                          The number of rows read from InnoDB tables.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Innodb_rows_updated
                          </h1>
                          The number of rows updated in InnoDB tables.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Key_blocks_not_flushed
                          </h1>
                          The number of key blocks in the key cache that have changed but have not yet 
                          been flushed to disk.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Key_blocks_unused
                          </h1>
                          The number of unused blocks in the key cache. You can use this value to determine 
                          how much of the key cache is in use.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Key_blocks_used
                          </h1>
                          The number of used blocks in the key cache. This value is a high-water mark 
                          that indicates the maximum number of blocks that have ever been in use at one time.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Key_read_requests
                          </h1>
                          The number of requests to read a key block from the cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Key_reads
                          </h1>
                          The number of physical reads of a key block from disk. If Key_reads is large, then 
                          your key_buffer_size value is probably too small. The cache miss rate can be calculated 
                          as Key_reads/Key_read_requests.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Key_write_requests
                          </h1>
                          The number of requests to write a key block to the cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Key_writes
                          </h1>
                          The number of physical writes of a key block to disk.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Last_query_cost
                          </h1>
                          The total cost of the last compiled query as computed by the query optimizer. 
                          This is useful for comparing the cost of different query plans for the same query. 
                          The default value of 0 means that no query has been compiled yet. The default value is 0. 
                          Last_query_cost has session scope.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Max_used_connections
                          </h1>
                          The maximum number of connections that have been in use simultaneously since the server started.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Not_flushed_delayed_rows
                          </h1>
                          The number of rows waiting to be written in INSERT DELAYED queues.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Open_files
                          </h1>
                          The number of files that are open. This count includes regular files opened by the server. 
                          It does not include other types of files such as sockets or pipes. Also, the count does not 
                          include files that storage engines open using their own internal functions rather than asking 
                          the server level to do so.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Open_streams
                          </h1>
                          The number of streams that are open (used mainly for logging).
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Open_table_definitions
                          </h1>
                          The number of cached .frm files.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Open_tables
                          </h1>
                          The number of tables that are open.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Opened_files
                          </h1>
                          The number of files that have been opened with my_open() (a mysys library function). Parts of the 
                          server that open files without using this function do not increment the count.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Opened_table_definitions
                          </h1>
                          The number of .frm files that have been cached.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Opened_tables
                          </h1>
                          The number of tables that have been opened. If Opened_tables is big, your table_open_cache value 
                          is probably too small.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Prepared_stmt_count
                          </h1>
                          The current number of prepared statements. (The maximum number of statements is given by the 
                          max_prepared_stmt_count system variable.)
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_free_blocks
                          </h1>
                          The number of free memory blocks in the query cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_free_memory
                          </h1>
                          The amount of free memory for the query cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_hits
                          </h1>
                          The number of query cache hits.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_inserts
                          </h1>
                          The number of queries added to the query cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_lowmem_prunes
                          </h1>
                          The number of queries that were deleted from the query cache because of low memory.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_not_cached
                          </h1>
                          The number of noncached queries (not cacheable, or not cached due to the query_cache_type setting).
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_queries_in_cache
                          </h1>
                          The number of queries registered in the query cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Qcache_total_blocks
                          </h1>
                          The total number of blocks in the query cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Queries
                          </h1>
                          The number of statements executed by the server. This variable includes statements executed 
                          within stored programs, unlike the Questions variable. It does not count COM_PING or COM_STATISTICS 
                          commands.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Questions
                          </h1>
                          The number of statements executed by the server. As of MySQL 5.1.31, this includes only statements 
                          sent to the server by clients and no longer includes statements executed within stored programs, 
                          unlike the Queries variable. This variable does not count COM_PING, COM_STATISTICS, COM_STMT_PREPARE, 
                          COM_STMT_CLOSE, or COM_STMT_RESET commands.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Rpl_status
                          </h1>
                          The status of fail-safe replication (not implemented).
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Select_full_join
                          </h1>
                          The number of joins that perform table scans because they do not use indexes. If this value is not 0, 
                          you should carefully check the indexes of your tables.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Select_full_range_join
                          </h1>
                          The number of joins that used a range search on a reference table.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Select_range
                          </h1>
                          The number of joins that used ranges on the first table. This is normally not a critical issue 
                          even if the value is quite large.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Select_range_check
                          </h1>
                          The number of joins without keys that check for key usage after each row. If this is not 0, 
                          you should carefully check the indexes of your tables.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Select_scan
                          </h1>
                          The number of joins that did a full scan of the first table.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Slave_open_temp_tables
                          </h1>
                          The number of temporary tables that the slave SQL thread currently has open. If the value is greater 
                          than zero, it is not safe to shut down the slave.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Slave_retried_transactions
                          </h1>
                          The total number of times since startup that the replication slave SQL thread has retried transactions.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Slave_running
                          </h1>
                          This is ON if this server is a replication slave that is connected to a replication master, 
                          and both the I/O and SQL threads are running; otherwise, it is OFF.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Slow_launch_threads
                          </h1>
                          The number of threads that have taken more than slow_launch_time seconds to create.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Slow_queries
                          </h1>
                          The number of queries that have taken more than long_query_time seconds.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Sort_merge_passes
                          </h1>
                          The number of merge passes that the sort algorithm has had to do. If this value is large, 
                          you should consider increasing the value of the sort_buffer_size system variable.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Sort_range
                          </h1>
                          The number of sorts that were done using ranges.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Sort_rows
                          </h1>
                          The number of sorted rows.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Sort_scan
                          </h1>
                          The number of sorts that were done by scanning the table.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Table_locks_immediate
                          </h1>
                          The number of times that a request for a table lock could be granted immediately.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Table_locks_waited
                          </h1>
                          The number of times that a request for a table lock could not be granted immediately 
                          and a wait was needed. If this is high and you have performance problems, you should 
                          first optimize your queries, and then either split your table or tables or use replication.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Tc_log_max_pages_used
                          </h1>
                          For the memory-mapped implementation of the log that is used by mysqld when it acts as 
                          the transaction coordinator for recovery of internal XA transactions, this variable indicates 
                          the largest number of pages used for the log since the server started.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Tc_log_page_size
                          </h1>
                          The page size used for the memory-mapped implementation of the XA recovery log. The default 
                          value is determined using getpagesize(). Currently, this variable is unused for the same reasons 
                          as described for Tc_log_max_pages_used.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Tc_log_page_waits
                          </h1>
                          For the memory-mapped implementation of the recovery log, this variable increments each time the 
                          server was not able to commit a transaction and had to wait for a free page in the log. If this value 
                          is large, you might want to increase the log size (with the --log-tc-size option).
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Threads_cached
                          </h1>
                          The number of threads in the thread cache.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Threads_connected
                          </h1>
                          The number of currently open connections.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Threads_created
                          </h1>
                          The number of threads created to handle connections. If Threads_created is big, you may want to increase 
                          the thread_cache_size value. The cache miss rate can be calculated as Threads_created/Connections.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Threads_running
                          </h1>
                          The number of threads that are not sleeping.
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h1 class="myclass2">
                              Uptime
                          </h1>
                          The number of seconds that the server has been up.
                      </td>
                  </tr>
              </table>
          </body>
      </html>
     ';

?>