-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Gegenereerd op: 30 okt 2024 om 09:04
-- Serverversie: 5.7.39
-- PHP-versie: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `intergarde`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `adressen`
--

CREATE TABLE `adressen` (
  `id` int(11) NOT NULL,
  `postcode` varchar(6) NOT NULL,
  `straatnaam` varchar(100) DEFAULT NULL,
  `huisnummer` varchar(9999) DEFAULT NULL,
  `plaats` varchar(100) DEFAULT NULL,
  `provincie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `adressen`
--

INSERT INTO `adressen` (`id`, `postcode`, `straatnaam`, `huisnummer`, `plaats`, `provincie_id`) VALUES
(1, '5611AA', 'Kleine Berg', '34', 'Eindhoven', 1),
(2, '6211AA', 'Vrijthof', '623', 'Maastricht', 3),
(3, '5911AA', 'Parade', '32', 'Venlo', 2),
(4, '5211AC', 'Bossche Markt', '75', 'Den Bosch', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `meldingen`
--

CREATE TABLE `meldingen` (
  `id` int(11) NOT NULL,
  `onderwerp` varchar(100) NOT NULL,
  `beschrijving` text NOT NULL,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `adressen_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Nieuw'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `meldingen`
--

INSERT INTO `meldingen` (`id`, `onderwerp`, `beschrijving`, `datum`, `adressen_id`, `status`) VALUES
(1, 'Camera kapot', 'De camera op de hoek van de straat werkt niet meer sinds 10:30 uur.', '2024-10-10 09:40:27', 1, 'Nieuw'),
(2, 'gfrgwrgwet', 'gtewgtwgtwr', '2024-10-30 08:53:45', 4, 'Nieuw');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `provincies`
--

CREATE TABLE `provincies` (
  `id` int(11) NOT NULL,
  `provincie_naam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `provincies`
--

INSERT INTO `provincies` (`id`, `provincie_naam`) VALUES
(1, 'Noord-Brabant'),
(2, 'Noord-Limburg'),
(3, 'Zuid-Limburg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`Id`, `Username`, `Email`, `Password`) VALUES
(1, 'test', 'test@test.nl', 'test'),
(2, 'bbk', 'bb@bb.nl', 'bb'),
(3, 'wadih', 'wadih@nl.nl', 'wadih');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `adressen`
--
ALTER TABLE `adressen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provincie_id` (`provincie_id`);

--
-- Indexen voor tabel `meldingen`
--
ALTER TABLE `meldingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adres_id` (`adressen_id`);

--
-- Indexen voor tabel `provincies`
--
ALTER TABLE `provincies`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `adressen`
--
ALTER TABLE `adressen`
  ADD CONSTRAINT `adressen_ibfk_1` FOREIGN KEY (`provincie_id`) REFERENCES `provincies` (`id`);

--
-- Beperkingen voor tabel `meldingen`
--
ALTER TABLE `meldingen`
  ADD CONSTRAINT `meldingen_ibfk_1` FOREIGN KEY (`adressen_id`) REFERENCES `Adressen` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
