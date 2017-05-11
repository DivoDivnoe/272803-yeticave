CREATE DATABASE yeticave DEFAULT CHARACTER SET utf8;

USE yeticave;

CREATE TABLE categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(31) NOT NULL UNIQUE
)ENGINE=InnoDB;

CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  register_date DATETIME,
  email VARCHAR(63) UNIQUE,
  name VARCHAR(63),
  password VARCHAR(63),
  avatar VARCHAR(63) UNIQUE,
  contacts VARCHAR(255)
)ENGINE=InnoDB;

CREATE TABLE lots (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category_id INT NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  register_date DATETIME,
  title VARCHAR(127),
  description TEXT(1023),
  image VARCHAR(63) UNIQUE,
  start_price DECIMAL(8,2),
  expire DATETIME,
  step INT,
  added_to_favorites INT,
  FOREIGN KEY(category_id) REFERENCES categories(id),
  FOREIGN KEY(author_id) REFERENCES users(id),
  FOREIGN KEY(winner_id) REFERENCES users(id)
)ENGINE=InnoDB;

CREATE TABLE bets (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,
  `date` DATETIME NOT NULL,
  `sum` DECIMAL(8,2) NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(id),
  FOREIGN KEY(lot_id) REFERENCES lots(id)
)ENGINE=InnoDB;

CREATE INDEX lots_price ON lots(start_price);
CREATE INDEX lots_title ON lots(title);
CREATE INDEX bets_sum ON bets(sum);
