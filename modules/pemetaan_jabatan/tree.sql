/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : anjab2

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2014-10-11 18:04:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `m_tree`
-- ----------------------------
DROP TABLE IF EXISTS `m_tree`;
CREATE TABLE `m_tree` (
  `id_tree` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_diagram` int(11) NOT NULL,
  `markup` varchar(200) DEFAULT NULL,
  `parent` int(11) NOT NULL,
  `id_jabatan` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tree`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of m_tree
-- ----------------------------
INSERT INTO `m_tree` VALUES ('1', '1', 'SEKRETARIS DAERAH', '0', '1');
INSERT INTO `m_tree` VALUES ('2', '1', 'ASISTEN III', '1', '2');
INSERT INTO `m_tree` VALUES ('3', '1', 'KEPALA', '2', '3');
INSERT INTO `m_tree` VALUES ('4', '1', 'JABATAN FUNGSIONAL', '2', '4');
INSERT INTO `m_tree` VALUES ('5', '1', 'KA SUB BAG KELEMBAGAAN', '3', '5');
INSERT INTO `m_tree` VALUES ('6', '1', 'KA SUB BAG ANALISIS FORMASI DAN JABATAN', '3', '6');
INSERT INTO `m_tree` VALUES ('7', '1', 'KA SUB BAG KETATALAKSANAAN', '3', '7');
INSERT INTO `m_tree` VALUES ('8', '1', 'Di bawah fungsional', '4', '8');
INSERT INTO `m_tree` VALUES ('9', '1', 'satu lagi', '4', '10');
INSERT INTO `m_tree` VALUES ('11', '1', 'tes', '4', '19');
INSERT INTO `m_tree` VALUES ('12', '1', 'oke', '4', '31');
INSERT INTO `m_tree` VALUES ('13', '1', 'tes', '9', '15');
INSERT INTO `m_tree` VALUES ('14', '1', 'fgf', '8', '18');
INSERT INTO `m_tree` VALUES ('15', '1', 'gghg', '11', '17');
INSERT INTO `m_tree` VALUES ('16', '1', 'hjhj', '15', '10');
INSERT INTO `m_tree` VALUES ('17', '1', 'trr', '15', '7');
INSERT INTO `m_tree` VALUES ('18', '1', 'jkjk', '16', '8');
INSERT INTO `m_tree` VALUES ('19', '1', 'jjh', '17', '66');
INSERT INTO `m_tree` VALUES ('20', '1', 'asad', '7', '8');
INSERT INTO `m_tree` VALUES ('21', '1', 'dds', '20', '69');
INSERT INTO `m_tree` VALUES ('22', '1', 'aasa', '20', '48');
INSERT INTO `m_tree` VALUES ('23', '1', 'sas', '20', '669');
INSERT INTO `m_tree` VALUES ('24', '1', 'aas', '15', '820');

-- ----------------------------
-- Table structure for `m_tree_jenis`
-- ----------------------------
DROP TABLE IF EXISTS `m_tree_jenis`;
CREATE TABLE `m_tree_jenis` (
  `id_jenis_diagram` int(11) NOT NULL AUTO_INCREMENT,
  `nama_diagram` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_jenis_diagram`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of m_tree_jenis
-- ----------------------------
INSERT INTO `m_tree_jenis` VALUES ('1', 'SETDA');
INSERT INTO `m_tree_jenis` VALUES ('2', 'ARPUSDA');
INSERT INTO `m_tree_jenis` VALUES ('3', 'BAPPEDA');
INSERT INTO `m_tree_jenis` VALUES ('4', 'BKBPP');
INSERT INTO `m_tree_jenis` VALUES ('5', 'BKD');
INSERT INTO `m_tree_jenis` VALUES ('6', 'BLH');
INSERT INTO `m_tree_jenis` VALUES ('7', 'BPMKP');
INSERT INTO `m_tree_jenis` VALUES ('8', 'DAMKAR');
INSERT INTO `m_tree_jenis` VALUES ('9', 'DINKES');
INSERT INTO `m_tree_jenis` VALUES ('10', 'DISNSOS');
INSERT INTO `m_tree_jenis` VALUES ('11', 'DISBUDPAR');
INSERT INTO `m_tree_jenis` VALUES ('12', 'DISDUKCAPIL');
INSERT INTO `m_tree_jenis` VALUES ('13', 'DISHUB');
INSERT INTO `m_tree_jenis` VALUES ('14', 'DISNAKER');
INSERT INTO `m_tree_jenis` VALUES ('15', 'DISPEN');
INSERT INTO `m_tree_jenis` VALUES ('16', 'DISPERINDAG');
INSERT INTO `m_tree_jenis` VALUES ('17', 'DISPORA');
INSERT INTO `m_tree_jenis` VALUES ('18', 'DKP');
INSERT INTO `m_tree_jenis` VALUES ('19', 'DPK');
INSERT INTO `m_tree_jenis` VALUES ('20', 'DPPKD');
INSERT INTO `m_tree_jenis` VALUES ('21', 'DPU');
INSERT INTO `m_tree_jenis` VALUES ('22', 'DTK');
INSERT INTO `m_tree_jenis` VALUES ('23', 'INSPEKTORAT');
INSERT INTO `m_tree_jenis` VALUES ('24', 'KECAMATAN');
INSERT INTO `m_tree_jenis` VALUES ('25', 'KELURAHAN');
INSERT INTO `m_tree_jenis` VALUES ('26', 'KESBANGLINMAS');
INSERT INTO `m_tree_jenis` VALUES ('27', 'KPM');
INSERT INTO `m_tree_jenis` VALUES ('28', 'RSUD');
INSERT INTO `m_tree_jenis` VALUES ('29', 'SATPOL PP');
INSERT INTO `m_tree_jenis` VALUES ('30', 'SETWAN');

-- ----------------------------
-- Table structure for `t_tree_jabatan`
-- ----------------------------
DROP TABLE IF EXISTS `t_tree_jabatan`;
CREATE TABLE `t_tree_jabatan` (
  `id_tree_jabatan` int(11) NOT NULL AUTO_INCREMENT,
  `id_tree` int(11) NOT NULL,
  `jml_jabatan` int(11) DEFAULT NULL,
  `tahun` int(4) DEFAULT NULL,
  `id_jabatan` int(11) NOT NULL,
  PRIMARY KEY (`id_tree_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of t_tree_jabatan
-- ----------------------------
INSERT INTO `t_tree_jabatan` VALUES ('1', '6', '1', '2014', '1');
INSERT INTO `t_tree_jabatan` VALUES ('2', '6', '1', '2014', '2');
INSERT INTO `t_tree_jabatan` VALUES ('3', '7', '1', '2014', '3');
INSERT INTO `t_tree_jabatan` VALUES ('4', '7', '1', '2014', '4');
INSERT INTO `t_tree_jabatan` VALUES ('5', '5', '1', '2014', '5');
INSERT INTO `t_tree_jabatan` VALUES ('6', '5', '1', '2014', '6');
INSERT INTO `t_tree_jabatan` VALUES ('7', '5', '1', '2014', '7');
