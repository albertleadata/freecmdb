
--
-- Table structure for table `mantis_plugin_freecmdb_itm`
--

--DROP TABLE IF EXISTS `mantis_plugin_freecmdb_itm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mantis_plugin_freecmdb_itm` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chg` datetime,
  `cat` bigint(20),
  `who` bigint(20),
  `name` varchar(80),
  `alias` varchar(80),
  `url` text,
  `wiki` text,
  `brief` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mantis_plugin_freecmdb_lut`
--

DROP TABLE IF EXISTS `mantis_plugin_freecmdb_lut`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mantis_plugin_freecmdb_lut` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created` datetime,
  `pid` bigint(20),
  `ptn` int(10),
  `idx` int(10),
  `name` varchar(80),
  `tag` varchar(80),
  `eid` bigint(20),
  `rid` bigint(20),
  `url` text,
  `brief` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mantis_plugin_freecmdb_log`
--

--DROP TABLE IF EXISTS `mantis_plugin_freecmdb_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mantis_plugin_freecmdb_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `created` datetime,
  `cat` int(10),
  `itm` bigint(20),
  `name` varchar(80),
  `brief` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

