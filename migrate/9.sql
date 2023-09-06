CREATE TABLE `nodes` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `host` text NOT NULL,
  `auth_key` text NOT NULL,
  `created-date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `nodes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `nodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;