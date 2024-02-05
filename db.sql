-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-02-2024 a las 14:22:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `project_vota`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `answers`
--

CREATE TABLE `answers` (
  `ID` int(11) NOT NULL,
  `Text` varchar(255) DEFAULT NULL,
  `PollID` int(11) DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `answers`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

CREATE TABLE `countries` (
  `ID` int(11) NOT NULL,
  `CountryName` varchar(250) NOT NULL,
  `PhoneCode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `countries`
--

INSERT INTO `countries` (`ID`, `CountryName`, `PhoneCode`) VALUES
(1, 'Australia', '+61'),
(2, 'Austria', '+43'),
(3, 'Azerbaiyán', '+994'),
(4, 'Anguilla', '+1-264'),
(5, 'Argentina', '+54'),
(6, 'Armenia', '+374'),
(7, 'Bielorrusia', '+375'),
(8, 'Belice', '+501'),
(9, 'Bélgica', '+32'),
(10, 'Bermudas', '+1-441'),
(11, 'Bulgaria', '+359'),
(12, 'Brasil', '+55'),
(13, 'Reino Unido', '+44'),
(14, 'Hungría', '+36'),
(15, 'Vietnam', '+84'),
(16, 'Haiti', '+509'),
(17, 'Guadalupe', '+590'),
(18, 'Alemania', '+49'),
(19, 'Países Bajos, Holanda', '+31'),
(20, 'Grecia', '+30'),
(21, 'Georgia', '+995'),
(22, 'Dinamarca', '+45'),
(23, 'Egipto', '+20'),
(24, 'Israel', '+972'),
(25, 'India', '+91'),
(26, 'Irán', '+98'),
(27, 'Irlanda', '+353'),
(28, 'España', '+34'),
(29, 'Italia', '+39'),
(30, 'Kazajstán', '+7'),
(31, 'Camerún', '+237'),
(32, 'Canadá', '+1'),
(33, 'Chipre', '+357'),
(34, 'Kirguistán', '+996'),
(35, 'China', '+86'),
(36, 'Costa Rica', '+506'),
(37, 'Kuwait', '+965'),
(38, 'Letonia', '+371'),
(39, 'Libia', '+218'),
(40, 'Lituania', '+370'),
(41, 'Luxemburgo', '+352'),
(42, 'México', '+52'),
(43, 'Moldavia', '+373'),
(44, 'Mónaco', '+377'),
(45, 'Nueva Zelanda', '+64'),
(46, 'Noruega', '+47'),
(47, 'Polonia', '+48'),
(48, 'Portugal', '+351'),
(49, 'Reunión', '+262'),
(50, 'Rusia', '+7'),
(51, 'El Salvador', '+503'),
(52, 'Eslovaquia', '+421'),
(53, 'Eslovenia', '+386'),
(54, 'Surinam', '+597'),
(55, 'Estados Unidos', '+1'),
(56, 'Tadjikistan', '+992'),
(57, 'Turkmenistan', '+993'),
(58, 'Islas Turcas y Caicos', '+1-649'),
(59, 'Turquía', '+90'),
(60, 'Uganda', '+256'),
(61, 'Uzbekistán', '+998'),
(62, 'Ucrania', '+380'),
(63, 'Finlandia', '+358'),
(64, 'Francia', '+33'),
(65, 'República Checa', '+420'),
(66, 'Suiza', '+41'),
(67, 'Suecia', '+46'),
(68, 'Estonia', '+372'),
(69, 'Corea del Sur', '+82'),
(70, 'Japón', '+81'),
(71, 'Croacia', '+385'),
(72, 'Rumanía', '+40'),
(73, 'Hong Kong', '+852'),
(74, 'Indonesia', '+62'),
(75, 'Jordania', '+962'),
(76, 'Malasia', '+60'),
(77, 'Singapur', '+65'),
(78, 'Taiwan', '886'),
(79, 'Bosnia y Herzegovina', '+387'),
(80, 'Bahamas', '+1-242'),
(81, 'Chile', '+56'),
(82, 'Colombia', '+57'),
(83, 'Islandia', '+354'),
(84, 'Corea del Norte', '+850'),
(85, 'Macedonia', '+389'),
(86, 'Malta', '+356'),
(87, 'Pakistán', '+92'),
(88, 'Papúa-Nueva Guinea', '+675'),
(89, 'Perú', '+51'),
(90, 'Filipinas', '+63'),
(91, 'Arabia Saudita', '+966'),
(92, 'Tailandia', '+66'),
(93, 'Emiratos árabes Unidos', '+971'),
(94, 'Groenlandia', '+299'),
(95, 'Venezuela', '58'),
(96, 'Zimbabwe', '+263'),
(97, 'Kenia', '+254'),
(98, 'Algeria', '+213'),
(99, 'Líbano', '+961'),
(100, 'Botsuana', '+267'),
(101, 'Tanzania', '+255'),
(102, 'Namibia', '+264'),
(103, 'Ecuador', '+593'),
(104, 'Marruecos', '+212'),
(105, 'Ghana', '+233'),
(106, 'Siria', '+963'),
(107, 'Nepal', '+977'),
(108, 'Mauritania', '+222'),
(109, 'Seychelles', '+248'),
(110, 'Paraguay', '+595'),
(111, 'Uruguay', '+598'),
(112, 'Congo (Brazzaville)', '+242'),
(113, 'Cuba', '+53'),
(114, 'Albania', '+355'),
(115, 'Nigeria', '+234'),
(116, 'Zambia', '+260'),
(117, 'Mozambique', '+258'),
(119, 'Angola', '+244'),
(120, 'Sri Lanka', '+94'),
(121, 'Etiopía', '+251'),
(122, 'Túnez', '+216'),
(123, 'Bolivia', '+591'),
(124, 'Panamá', '+507'),
(125, 'Malawi', '+265'),
(126, 'Liechtenstein', '+423'),
(127, 'Bahrein', '+973'),
(128, 'Barbados', '+1246'),
(130, 'Chad', '+235'),
(131, 'Man, Isla de', '+44 1624'),
(132, 'Jamaica', '+1876'),
(133, 'Malí', '+223'),
(134, 'Madagascar', '+261'),
(135, 'Senegal', '+221'),
(136, 'Togo', '+228'),
(137, 'Honduras', '+504'),
(138, 'República Dominicana', '+1809'),
(139, 'Mongolia', '+976'),
(140, 'Irak', '+964'),
(141, 'Sudáfrica', '+27'),
(142, 'Aruba', '+297'),
(143, 'Gibraltar', '+350'),
(144, 'Afganistán', '+93'),
(145, 'Andorra', '+376'),
(147, 'Antigua y Barbuda', '+1268'),
(149, 'Bangladesh', '+880'),
(151, 'Benín', '+229'),
(152, 'Bután', '+975'),
(154, 'Islas Vírgenes Británicas', '+1284'),
(155, 'Brunéi', '+673'),
(156, 'Burkina Faso', '+226'),
(157, 'Burundi', '+257'),
(158, 'Camboya', '+855'),
(159, 'Cabo Verde', '+238'),
(164, 'Comores', '+269'),
(165, 'Congo (Kinshasa)', '+243'),
(166, 'Cook, Islas', '+682'),
(168, 'Costa de Marfil', '+225'),
(169, 'Djibouti, Yibuti', '+253'),
(171, 'Timor Oriental', '+670'),
(172, 'Guinea Ecuatorial', '+240'),
(173, 'Eritrea', '+291'),
(175, 'Feroe, Islas', '+298'),
(176, 'Fiyi', '+679'),
(178, 'Polinesia Francesa', '+689'),
(180, 'Gabón', '+241'),
(181, 'Gambia', '+220'),
(184, 'Granada', '+1473'),
(185, 'Guatemala', '+502'),
(186, 'Guernsey', '+44 1481'),
(187, 'Guinea', '+224'),
(188, 'Guinea-Bissau', '+245'),
(189, 'Guyana', '+592'),
(193, 'Jersey', '+44 1534'),
(195, 'Kiribati', '+686'),
(196, 'Laos', '+856'),
(197, 'Lesotho', '+266'),
(198, 'Liberia', '+231'),
(200, 'Maldivas', '+960'),
(201, 'Martinica', '+596'),
(202, 'Mauricio', '+230'),
(205, 'Myanmar', '+95'),
(206, 'Nauru', '+674'),
(207, 'Antillas Holandesas', '+599'),
(208, 'Nueva Caledonia', '+687'),
(209, 'Nicaragua', '+505'),
(210, 'Níger', '+227'),
(212, 'Norfolk Island', '+672'),
(213, 'Omán', '+968'),
(215, 'Isla Pitcairn', '+64'),
(216, 'Qatar', '+974'),
(217, 'Ruanda', '+250'),
(218, 'Santa Elena', '+290'),
(219, 'San Cristobal y Nevis', '+1869'),
(220, 'Santa Lucía', '+1758'),
(221, 'San Pedro y Miquelón', '+508'),
(222, 'San Vincente y Granadinas', '+1784'),
(223, 'Samoa', '+685'),
(224, 'San Marino', '+378'),
(225, 'San Tomé y Príncipe', '+239'),
(226, 'Serbia y Montenegro', '+381'),
(227, 'Sierra Leona', '+232'),
(228, 'Islas Salomón', '+677'),
(229, 'Somalia', '+252'),
(232, 'Sudán', '+249'),
(234, 'Swazilandia', '+268'),
(235, 'Tokelau', '+690'),
(236, 'Tonga', '+676'),
(237, 'Trinidad y Tobago', '+1868'),
(239, 'Tuvalu', '+688'),
(240, 'Vanuatu', '+678'),
(241, 'Wallis y Futuna', '+681'),
(242, 'Sáhara Occidental', '+212'),
(243, 'Yemen', '+967'),
(246, 'Puerto Rico', '+1787');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `PollID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `email_queue`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_recovery_requests`
--

CREATE TABLE `password_recovery_requests` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `request_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Table structure for table `Poll_InvitedUsers`
--

DROP TABLE IF EXISTS `Poll_InvitedUsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Poll_InvitedUsers` (
  `UserID` int DEFAULT NULL,
  `PollID` int DEFAULT NULL,
  `tokenQuestion` varchar(255) DEFAULT NULL,
  UNIQUE KEY `Poll_InvitedUsers_unique` (`UserID`,`PollID`),
  KEY `Poll_InvitedUsers_Poll` (`PollID`),
  CONSTRAINT `Poll_InvitedUsers_Poll` FOREIGN KEY (`PollID`) REFERENCES `Polls` (`ID`),
  CONSTRAINT `Poll_InvitedUsers_User` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Volcado de datos para la tabla `password_recovery_requests`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `polls`
--

CREATE TABLE `polls` (
  `ID` int(11) NOT NULL,
  `Question` varchar(255) DEFAULT NULL,
  `CreationDate` datetime DEFAULT NULL,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  `State` enum('active','blocked','not_begun','finished') DEFAULT NULL,
  `QuestionVisibility` enum('public','private','hidden') DEFAULT NULL,
  `ResultsVisibility` enum('public','private','hidden') DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `CreatorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `polls`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `poll_admins`
--

CREATE TABLE `poll_admins` (
  `UserID` int(11) DEFAULT NULL,
  `PollID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User_Vote`
--

DROP TABLE IF EXISTS `User_Vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User_Vote` (
  `UserID` int DEFAULT NULL,
  `AnswerID` int DEFAULT NULL,
  `PollID` int(11) DEFAULT NULL,
  UNIQUE KEY `User_Vote_unique` (`UserID`,`AnswerID`),
  KEY `User_Vote_Answer` (`AnswerID`),
  KEY `PollID` (`PollID`),
  CONSTRAINT `User_Vote_Answer` FOREIGN KEY (`AnswerID`) REFERENCES `Answers` (`ID`),
  CONSTRAINT `User_Vote_User` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`),
  CONSTRAINT `user_vote_ibfk_1` FOREIGN KEY (`PollID`) REFERENCES `polls` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Volcado de datos para la tabla `poll_invitedusers`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Phone` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `PostalCode` int(11) DEFAULT NULL,
  `IsAuthenticated` tinyint(1) DEFAULT NULL,
  `ValidationToken` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_vote`
--

CREATE TABLE `user_vote` (
  `UserID` varchar(255)(11) DEFAULT NULL,
  `AnswerID` int(11) DEFAULT NULL,
  `PollID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Answers_Poll` (`PollID`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_recovery_requests`
--
ALTER TABLE `password_recovery_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_PollCreator` (`CreatorID`);

--
-- Indices de la tabla `poll_admins`
--
ALTER TABLE `poll_admins`
  ADD UNIQUE KEY `Poll_Admins_unique` (`UserID`,`PollID`),
  ADD KEY `Poll_Admins_Poll` (`PollID`);

--
-- Indices de la tabla `poll_invitedusers`
--
ALTER TABLE `poll_invitedusers`
  ADD UNIQUE KEY `Poll_InvitedUsers_unique` (`UserID`,`PollID`),
  ADD KEY `Poll_InvitedUsers_Poll` (`PollID`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `user_vote`
--
ALTER TABLE `user_vote`
  ADD UNIQUE KEY `User_Vote_unique` (`UserID`,`AnswerID`),
  ADD KEY `User_Vote_Answer` (`AnswerID`),
  ADD KEY `PollID` (`PollID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `answers`
--
ALTER TABLE `answers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `countries`
--
ALTER TABLE `countries`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT de la tabla `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `polls`
--
ALTER TABLE `polls`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `Answers_Poll` FOREIGN KEY (`PollID`) REFERENCES `polls` (`ID`);

--
-- Filtros para la tabla `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `FK_PollCreator` FOREIGN KEY (`CreatorID`) REFERENCES `users` (`ID`);

--
-- Filtros para la tabla `poll_admins`
--
ALTER TABLE `poll_admins`
  ADD CONSTRAINT `Poll_Admins_Admin` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `Poll_Admins_Poll` FOREIGN KEY (`PollID`) REFERENCES `polls` (`ID`);

--
-- Filtros para la tabla `poll_invitedusers`
--
ALTER TABLE `poll_invitedusers`
  ADD CONSTRAINT `Poll_InvitedUsers_Poll` FOREIGN KEY (`PollID`) REFERENCES `polls` (`ID`),
  ADD CONSTRAINT `Poll_InvitedUsers_User` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`);

--
-- Filtros para la tabla `user_vote`
--
ALTER TABLE `user_vote`
  ADD CONSTRAINT `User_Vote_Answer` FOREIGN KEY (`AnswerID`) REFERENCES `answers` (`ID`),
  ADD CONSTRAINT `User_Vote_User` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `user_vote_ibfk_1` FOREIGN KEY (`PollID`) REFERENCES `polls` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
