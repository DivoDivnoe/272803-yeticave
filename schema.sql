CREATE DATABASE yeticave DEFAULT CHARACTER SET utf8;

USE yeticave;

CREATE TABLE categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(31) NOT NULL UNIQUE
)ENGINE=InnoDB;

CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  register_date DATETIME NOT NULL,
  email VARCHAR(63) UNIQUE NOT NULL,
  name VARCHAR(63) NOT NULL,
  password VARCHAR(63) NOT NULL,
  avatar VARCHAR(63),
  contacts VARCHAR(255)
)ENGINE=InnoDB;

CREATE TABLE lots (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category_id INT NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  register_date DATETIME NOT NULL,
  title VARCHAR(127) NOT NULL,
  description TEXT(1023) NOT NULL,
  image VARCHAR(63) UNIQUE NOT NULL,
  start_price DECIMAL(8,2) NOT NULL,
  expire DATETIME NOT NULL,
  step INT NOT NULL,
  added_to_favorites INT,
  FOREIGN KEY(category_id) REFERENCES categories(id),
  FOREIGN KEY(author_id) REFERENCES users(id),
  FOREIGN KEY(winner_id) REFERENCES users(id)
)ENGINE=InnoDB;

CREATE TABLE bets (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,
  date DATETIME NOT NULL,
  sum DECIMAL(8,2) NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(id),
  FOREIGN KEY(lot_id) REFERENCES lots(id)
)ENGINE=InnoDB;

CREATE INDEX lots_price ON lots(start_price);
CREATE INDEX lots_title ON lots(title);
CREATE INDEX bets_sum ON bets(sum);
