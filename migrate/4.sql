CREATE TABLE `resetpasswords` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT NULL,
  `user-apikey` text NOT NULL,
  `user-resetkeycode` text NOT NULL,
  `ip_addres` text NOT NULL,
  `status` enum('valid','unknown','expired') NOT NULL DEFAULT 'valid',
  `dateinfo` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `resetpasswords`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `resetpasswords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;