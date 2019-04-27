
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `plost` (
  `id` int(6) UNSIGNED NOT NULL,
  `pname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `age` int(6) UNSIGNED NOT NULL,
  `place` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subdistrict` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `district` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `city` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `detail` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `specific` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `status` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `type_id` int(6) UNSIGNED NOT NULL,
  `guest_id` int(6) UNSIGNED NOT NULL DEFAULT '0',
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `feedback_id` int(6) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `plost` (`id`, `fname`, `lname`,`gender`,`age`,`place`,`detail`,`status`,`type_id`,`guest_id`,`reg_date`) VALUES

(1, `เด็กชาย`, `ธนโชติ`, `ดิษวงษ์`, `M`, 12, `บริเวณสำนักสงฆ์ปฏิบัติธรรมชายธงเจริญธรรม ตำบลเขาชายธง อำเภอตากฟ้า จังหวัดนครสวรรค์`, `สูง 136 ซม. น้ำหนักประมาณ 26 กก. ผมเกรียน วันที่หายสวมผ้าไตรจีวรสีส้ม`,`ยังไม่พบ`, 6,1,`2017-03-28 14:34:32`),
(2, `นาย`, `สมคิด`, `ทาบุญมา`, `M`, 45, `บริเวณหมู่บ้านหนองแปน ตำบลเชียงเพ็ง อำเภอกุดจับ จังหวัดอุดรธานี `, `รูปร่างผอม สูงประมาณ 165 ซม. ผิวสีดำ-แดง ลักษณะผมสั้น สีผมดำ-ขาว`,`ยังไม่พบ`, 6 ,2,`2017-03-30 04:00:53`),
(3, `น.ส.`, `จินตนา`, `แซ่เตีย`, `F`, 61, `บริเวณหมู่บ้านกรีนการ์เด้นท์โฮม 4 ตำบลลำลูกกา อำเภอลำลูกกา จังหวัดปทุมธานี`,`รูปร่างผอม สูงประมาณ 160 ซม. ผิวสีขาว ลักษณะผมยาว สีผมดำ-ขาว การแต่งกายสวมเสื้อยืดสีน้ำตาล สวมกางเกงขายาวสีเขียวอ่อน`,`ยังไม่พบ`,7,3,`2017-03-28 14:34:32`);


CREATE TABLE `type` (
  `type_id` int(6) UNSIGNED NOT NULL,
  `type_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `type` (`type_id`, `type_name`) VALUES
(1,'ลักพาตัว'),
(2,'เด็กพลัดหลง'),
(3,'จิตเวท'),
(4,'พัฒนาการทางสมองช้า'),
(5,'แย้งความปกครองบุตร'),
(6,'สุขภาพจิต'),
(7,'อาการทางสมอง');

CREATE TABLE `invert` (
  `invert_id` int(6) NOT NULL AUTO_INCREMENT,
  `term` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `idf` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `Guest` (
  `guest_id` int(6) NOT NULL AUTO_INCREMENT,
  `guest_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `guest_email` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `guest_place` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `guest_phone` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `guest_pass` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `invert`
  ADD PRIMARY KEY (`invert_id`);


ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);


ALTER TABLE `peoplelost`
  ADD PRIMARY KEY (`id`);


  ALTER TABLE `Guest`
    ADD PRIMARY KEY (`guest_id`);
