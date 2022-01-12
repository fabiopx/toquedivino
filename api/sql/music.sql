-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 20-Out-2021 às 18:26
-- Versão do servidor: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toquedivino`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `music`
--

CREATE TABLE `music` (
  `idmusic` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `status` int(11) DEFAULT '1' COMMENT '0 = disable\n1 = enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `music`
--

INSERT INTO `music` (`idmusic`, `name`, `url`, `status`) VALUES
(1, '93 million miles – Jason Mraz', 'https://dl.dropbox.com/s/ckmpecb1rv98zui/02.mp3', 1),
(2, ' 9º sinfonia – L. V. Beethoven', 'https://dl.dropbox.com/s/u4hsy0n73oc0ox1/01.mp3', 1),
(3, 'A bela e a fera – Celine Dion', 'https://dl.dropbox.com/s/w9u388rtgxg6k4a/03.mp3', 1),
(4, 'A thousand years – Chistina Perri', ' https://dl.dropbox.com/s/f5hnpqiwu45yk9d/04.mp3', 1),
(5, 'A whole new world (Alladin) – Peabo Bryson', 'https://dl.dropbox.com/s/0sfhhhl9m8n3sne/05.mp3', 1),
(6, ' Accidentaly in love – Counting crows', 'https://dl.dropbox.com/s/gvuw4pn1px2xac9/06.mp3', 1),
(7, 'Agnus Dei', 'https://dl.dropbox.com/s/jb98nj9o0e8bzy5/07.mp3', 1),
(8, 'Alecrim', 'https://dl.dropbox.com/s/t5dkquo7miz8fkv/08.mp3', 1),
(9, 'Alegria (Cirque du Soleil)', 'https://dl.dropbox.com/s/yufzetqi9k9u20h/09.mp3', 1),
(10, 'All of me - John Legend', ' https://dl.dropbox.com/s/i93w7892dtkndxx/118.mp3', 1),
(11, 'All star – Cassia Eller', 'https://dl.dropbox.com/s/6rsg1t7i8a2gdd9/10.mp3', 1),
(12, 'All you need is love – Beatles', 'https://dl.dropbox.com/s/ihl4ur8m549gu6u/11.mp3', 1),
(13, ' Also sprech ZarathustraOdisseia–Richard Strauss', ' https://dl.dropbox.com/s/ltwaespylfdgfxt/12.mp3', 1),
(14, 'Always on my mind – Elvis Presley', 'https://dl.dropbox.com/s/wfuw5meyszut0wg/13.mp3', 1),
(15, 'Angels – Robbie Willians', 'https://dl.dropbox.com/s/h6z4fs61pdzjoir/14.mp3', 1),
(16, 'Aquarela – Toquinho', 'https://dl.dropbox.com/s/m5a3vifebo74397/15.mp3', 1),
(17, ' Aria da corda Sol – J. S. Bach', 'https://dl.dropbox.com/s/q5vm47qlaq2i3is/16.mp3', 1),
(18, 'At your side – The Corrs', 'https://dl.dropbox.com/s/j8d1rtukomoe7r8/17.mp3', 1),
(19, 'Ave Maria (Cantada) – J. S. Bach – Gounod', 'https://dl.dropbox.com/s/0z4m5dnjipz2m03/18_1.mp3', 1),
(20, 'Ave Maria (instrumental) – J. S. Bach – Gounod', 'https://dl.dropbox.com/s/jk33nhmae619j4e/18.mp3', 1),
(21, 'Ave Maria – F. Schubert', 'https://dl.dropbox.com/s/tf8dgawn6wpfuig/19.mp3', 1),
(22, 'Beautiful day – U2', ' https://dl.dropbox.com/s/jnns6shlw3057kk/20.mp3', 1),
(23, 'Bendito seras – Toque no altar', 'https://dl.dropbox.com/s/gxad482xlhhk3ot/21.mp3', 1),
(24, 'Bittersweet symphony – The Verve', 'https://dl.dropbox.com/s/srn3pv3urvb3hvm/22.mp3', 1),
(25, ' Bolero – M. Ravel', 'https://dl.dropbox.com/s/ck86z1dzwj2rcai/23.mp3', 1),
(26, ' Brave heart (coracao valente) – James Horner', 'https://dl.dropbox.com/s/gtk2h9ldgino9th/24.mp3', 1),
(27, 'Bumblebee Captured(transformers)–Steve Jablonsky', 'https://dl.dropbox.com/s/9rjjixvj2u7d6mz/25.mp3', 1),
(28, 'Can You Feel The Love Tonight(Rei Leao)Elton John', ' https://dl.dropbox.com/s/ktfsjoqma83vx01/26.mp3', 1),
(29, ' Canon in D – Pachelbel', 'https://dl.dropbox.com/s/8isro96ihwdep1c/27.mp3', 1),
(30, 'Ceu de Santo Amaro(Sinfonia da cantata 156)–J.S.Bach', 'https://dl.dropbox.com/s/qm5ggbexe9dt8iu/28.mp3', 1),
(31, 'Cinema paradiso (love theme)Ennio Morricone', 'https://dl.dropbox.com/s/068kxhgant0l9xn/29.mp3', 1),
(32, ' Clarinada (Mahler)', ' https://dl.dropbox.com/s/oizym7cgplqb5hm/30.mp3', 1),
(33, 'Clarinada (Rainha)', 'https://dl.dropbox.com/s/2dtmn3zjjvkzgp7/31.mp3', 1),
(34, 'Clocks – Cold Play', 'https://dl.dropbox.com/s/9otdfmf1243mbd1/32.mp3', 1),
(35, 'Como e grande o meu amor por voce – Roberto Carlos', 'https://dl.dropbox.com/s/2juetsamy97zwxd/33.mp3', 1),
(36, ' Con te partiro – Andrea Bocelli', 'https://dl.dropbox.com/s/5k7xd30o43vpjl7/34.mp3', 1),
(37, 'Concerning the Hobbits(O Senhor dos aneis)H.Shore', 'https://dl.dropbox.com/s/02pyvkqlbqv75ju/35.mp3', 1),
(38, 'Conquista do paraiso - Vangelis ', ' https://dl.dropbox.com/s/gajo2iixznajhlf/36.mp3', 1),
(39, 'Counting Stars - OneReuplic', ' https://dl.dropbox.com/s/yz359ba3rfd8oqj/117.mp3', 1),
(40, ' Darth Weider\'s theme (Star Wars) - John Willians', 'https://dl.dropbox.com/s/8c1xjvtxwrpakoe/37.mp3', 1),
(41, ' Diamonds (In the sky) – Rihanna', 'https://dl.dropbox.com/s/k79hh9quf1zehpa/38.mp3', 1),
(42, ' Divertimento – Saint Preux', 'https://dl.dropbox.com/s/zih29drqg8d6bar/39.mp3', 1),
(43, 'Duas metades - Jorge e Mateus', 'https://dl.dropbox.com/s/afgmpifb9yns63k/119.mp3', 1),
(44, 'Dust in the wind – Sarah Brightman', 'https://dl.dropbox.com/s/hf2tx5sc15rojna/40.mp3', 1),
(45, 'Dying young (Tudo por amor) – Kenny G', 'https://dl.dropbox.com/s/ria9okeevii9i2f/41.mp3', 1),
(46, 'Endless love – D. Ross e L. Richie', 'https://dl.dropbox.com/s/jl4q6kw1sbpct9m/42.mp3', 1),
(47, 'Eu e minha casa – Andre Valadao', 'https://dl.dropbox.com/s/gz2tsz6v3pv4ck0/43.mp3', 1),
(48, ' Eu sei que vou te amar – Tom Jobim', 'https://dl.dropbox.com/s/a8rk743pj59s7hz/44.mp3', 1),
(49, ' Everething I do I do it for you – Bryan Adams', 'https://dl.dropbox.com/s/oxoe36cawiem790/111.mp3', 1),
(50, 'Everything – Michael Buble', 'https://dl.dropbox.com/s/nx0pz0d9k8jp3gx/45.mp3', 1),
(51, 'Firework-Katty Perry', 'https://dl.dropbox.com/s/f93zq39o5r6pln6/112.mp3', 1),
(52, 'Forever in love – Kenny G', 'https://dl.dropbox.com/s/kiuzvierk4jor9t/47.mp3', 1),
(53, 'Forever – Kiss', 'https://dl.dropbox.com/s/ei7v4notia98iy6/46.mp3', 1),
(54, 'Greensleaves', 'https://dl.dropbox.com/s/pbds949u1fgnvv2/113.mp3', 1),
(55, 'Hall of fame – The Script', 'https://dl.dropbox.com/s/fz7hzwjy8tvlfjp/114.mp3', 1),
(56, ' Hallelujah (Shrek) – Leonard Cohen', 'https://dl.dropbox.com/s/2r5td7wuvssy5mk/48.mp3', 1),
(57, 'Halo - Beyonce', 'https://dl.dropbox.com/s/k0zt606xqiticob/115.mp3', 1),
(58, ' Happy - Pharrel Willians', 'https://dl.dropbox.com/s/cdduciddcdetw6a/120.mp3', 1),
(59, 'Havent met you yet - Michael Buble', 'https://dl.dropbox.com/s/i8la1o1r0oxng04/116.mp3', 1),
(60, 'Hero – Mariah Carey', 'https://dl.dropbox.com/s/y8swq1xb46qosq8/49.mp3', 1),
(61, ' Hey soul sisters – Train', 'https://dl.dropbox.com/s/ojuz0y3p52yg0qx/50.mp3', 1),
(62, ' Hollywood – Michael Buble', 'https://dl.dropbox.com/s/bb240qtcdwv3yl9/51.mp3', 1),
(63, ' I cant help falling in love – Elvis Presley', 'https://dl.dropbox.com/s/slc91qkt5bisbwq/52.mp3', 1),
(64, 'I gotta felling – Black Eyed Peas', ' https://dl.dropbox.com/s/9u3kd7nkm9myywd/53.mp3', 1),
(65, ' I say a little prayer for you – Burt Bacharach', 'https://dl.dropbox.com/s/bg78mdty8ptd0wa/54.mp3', 1),
(66, ' Im a believer (Shrek) – Smash mouth', 'https://dl.dropbox.com/s/winkdcvymylxy3k/55.mp3', 1),
(67, ' Im glad you came – The wanted', 'https://dl.dropbox.com/s/gg9t7o5t051mlrx/56.mp3', 1),
(68, ' Im yours – Jason Mraz', 'https://dl.dropbox.com/s/u1glhk7uqdg76pn/57.mp3', 1),
(69, 'Immortality – Celine Dion', 'https://dl.dropbox.com/s/uqm4fhqogn2a2yw/58.mp3', 1),
(70, 'In the arms of an angel – Sarah McLachan', 'https://dl.dropbox.com/s/yphc4xb7z4lhe9d/59.mp3', 1),
(71, ' Iris – Goo goo dolls', 'https://dl.dropbox.com/s/rbx0g17a3czx0kw/60.mp3', 1),
(72, 'Jesus alegria dos homens – J. S. Bach', 'https://dl.dropbox.com/s/32oljk5hm4q55i8/61.mp3', 1),
(73, 'Just the way you are – Bruno mars', 'https://dl.dropbox.com/s/hteu9vkv3a1z8d2/62.mp3', 1),
(74, 'Locked out of heaven - Bruno Mars', 'https://dl.dropbox.com/s/hyikve8vpwnyynl/121.mp3', 1),
(75, 'Lucky – Jason Mraz e Colbie Cailat', 'https://dl.dropbox.com/s/3xqf8gg8rapatlp/63.mp3', 1),
(76, ' Luna – Alessandro Safina', 'https://dl.dropbox.com/s/36e91j3kgzr20am/64.mp3', 1),
(77, 'Marcha nupcial – Felix Mendelson', ' https://dl.dropbox.com/s/mp507y42fui9kz0/65.mp3', 1),
(78, 'Marcha nupcial – Richard Wagner', 'https://dl.dropbox.com/s/ntcfmghrh06h944/66.mp3', 1),
(79, 'Minueto em G – J. S. Bach ', 'https://dl.dropbox.com/s/vmwt1wdazt0xkzt/67.mp3', 1),
(80, 'Need you now – Lady Antebellum ', 'https://dl.dropbox.com/s/z30m83wep7acskh/68.mp3', 1),
(81, 'Nothing else matters – Metalica ', ' https://dl.dropbox.com/s/2x5ahqbmin3sqeu/69.mp3', 1),
(82, 'November rain – Guns \'n roses', 'https://dl.dropbox.com/s/1ixvac0g8dyy9c3/70.mp3', 1),
(83, ' Oh happy day – Kirk Franklin ', 'https://dl.dropbox.com/s/u2wwjh0srhtvjwu/71.mp3', 1),
(84, 'Only hope – Mandy Moore', 'https://dl.dropbox.com/s/nvqw0ozcculd1ub/72.mp3', 1),
(85, 'Oracao pela familia – Pe Zezinho', 'https://dl.dropbox.com/s/2gbelzx03lth0ad/73.mp3', 1),
(86, 'Os anjos cantam nosso amor - Jorge e Mateus', 'https://dl.dropbox.com/s/5mrquyxx92dts3h/122.mp3', 1),
(87, 'Over the rainbow (O magico de Oz)–Harold Arlen', 'https://dl.dropbox.com/s/uot5hws7mx05j6d/74.mp3', 1),
(88, ' Pai nosso (versao Pe Marcelo Rossi)', 'https://dl.dropbox.com/s/8krr1raecwzt32u/75.mp3', 1),
(89, 'Panis angelicus – S. T. De Aquino', 'https://dl.dropbox.com/s/n59b2tbwhh7xh51/76.mp3', 1),
(90, 'Paradise – Cold Play', 'https://dl.dropbox.com/s/j8v667b296ce1tt/77.mp3', 1),
(91, 'Perfect - Ed Sheeran ', 'https://naijahitsongs.com/wp-content/uploads/2018/05/Ed_Sheeran_-_Perfect.mp3', 1),
(92, 'Perhaps love – John Denver', 'https://dl.dropbox.com/s/uuqik3t5htii2nw/78.mp3', 1),
(93, 'Photograph - Ed Sheeran', 'https://dl.dropbox.com/s/kqanmp1wcxv74gr/123.mp3', 1),
(94, 'Piruetas – Chico Buarque e os trapalhoes', 'https://dl.dropbox.com/s/waguzvnoo17mk6w/79.mp3', 1),
(95, 'Pompa e circunstancia – Edward elgar', 'https://dl.dropbox.com/s/evxqetw2399vftg/80.mp3', 1),
(96, 'Primavera – A. Vivaldi ', 'https://dl.dropbox.com/s/0ztrkpukzc8z8wd/81.mp3', 1),
(97, 'Reach – Gloria Estefan', 'https://dl.dropbox.com/s/6ca2o8pqo9lbiz6/82.mp3', 1),
(98, ' Roar - Katty Perry ', 'https://dl.dropbox.com/s/dmoauyn3tczmzs7/83.mp3', 1),
(99, 'Runaway – The Corrs', 'https://dl.dropbox.com/s/iqji5vg64pjtxj3/84.mp3', 1),
(100, 'Set fire to the rain – Adele ', 'https://dl.dropbox.com/s/2il6z55dd39k11l/85.mp3', 1),
(101, ' Simply the best – Tina Turner', 'https://dl.dropbox.com/s/elqy91z3cdn78d4/86.mp3', 1),
(102, 'Skyfall – Adele', 'https://dl.dropbox.com/s/p5siumnx86u7xfa/87.mp3', 1),
(103, 'Someone like you – Adele ', ' https://dl.dropbox.com/s/7xbfo9rpfii4mj6/88.mp3', 1),
(104, 'Someone like you – Boys like girls ', 'https://dl.dropbox.com/s/ms013p1a0go5uyg/89.mp3', 1),
(105, 'Songbird – Kenny G', 'https://dl.dropbox.com/s/dy75dve163477le/90.mp3', 1),
(106, ' Sweet child o\' mine – Guns \'n roses', 'https://dl.dropbox.com/s/7jt00goeo98r8kg/91.mp3', 1),
(107, ' The lion sleeps tonight (Rei leao) – the Tokens ', ' https://dl.dropbox.com/s/0g235t7zibh0ede/92.mp3', 1),
(108, 'The moment – Kenny G', 'https://dl.dropbox.com/s/65d7pn448a937nl/93.mp3', 1),
(109, 'The prayer – C. Dion e A. Bocelli', 'https://dl.dropbox.com/s/0s87ud9k6ye74x8/94.mp3', 1),
(110, ' Thinking of you – Katty Perry ', 'https://dl.dropbox.com/s/0b65m2vwi5qwxgb/95.mp3', 1),
(111, 'Thinking out loud - Ed Sheeran ', 'https://dl.dropbox.com/s/6kdqhxwqiybz84v/124.mp3', 1),
(112, 'This love – Maroon 5', 'https://dl.dropbox.com/s/mqvkro7j2ir5w2w/96.mp3', 1),
(113, 'Titanium – David Guetta ', 'https://dl.dropbox.com/s/a2pjhmt3d5nrh7z/97.mp3', 1),
(114, 'Top gun (Theme song) – Steve Stevens ', 'https://dl.dropbox.com/s/3b4oa4m4kkqok87/98.mp3', 1),
(115, 'Trumpet voluntary – Henry Purcell', 'https://dl.dropbox.com/s/k4o5352adzp35oj/99.mp3', 1),
(116, 'Tudo o que se quer (Fantasma da opera) – A. L. Webber ', 'https://dl.dropbox.com/s/0t5zidkv37wd6vw/100.mp3', 1),
(117, 'Um amor puro – Djavan', 'https://dl.dropbox.com/s/7sbed7t5fhqxrvm/101.mp3', 1),
(118, 'Viva la vida – Cold Play ', 'https://dl.dropbox.com/s/eil94h9d873aw2d/102.mp3', 1),
(119, ' We are young – Janelle Monaet ', 'https://dl.dropbox.com/s/dib97xdz2xydffc/103.mp3', 1),
(120, 'What a wonderful world – Louis Armstrong', 'https://dl.dropbox.com/s/wyq6rc4k7tbhsqz/104.mp3', 1),
(121, 'When you wish upon the a star (Peter pan)', 'https://dl.dropbox.com/s/16pc5wcxy0h0o0s/105.mp3', 1),
(122, 'Wind of change - scorpions', 'https://dl.dropbox.com/s/nvwlkpguixs6qzx/106.mp3', 1),
(123, 'Without you – David Guetta', 'https://dl.dropbox.com/s/bxd5zriiu1iq654/107.mp3', 1),
(124, 'You are still the one – Shania Twain ', 'https://dl.dropbox.com/s/pdzfw4q39zv4qjl/108.mp3', 1),
(125, 'You\'ll be in my heart (Tarzan) – Phil Collins', ' https://dl.dropbox.com/s/356ppc46hv0fmx0/109.mp3', 1),
(126, 'Your Song (Moulin rouge) – Elton John ', 'https://dl.dropbox.com/s/daks1enjjpjet2q/110.mp3', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`idmusic`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `idmusic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
