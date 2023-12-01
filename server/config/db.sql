CREATE DATABASE book_db;
USE book_db;
CREATE TABLE bookmarks(
  id MEDIUMINT NOT NULL AUTO_INCREMENT,
  url VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,
  date_added DATETIME NOT NULL,
  PRIMARY KEY (id)
);
INSERT INTO bookmarks (url, title, date_added) VALUES 
('https://www.twitter.com', 'Twitter', NOW()),
('https://www.youtube.com', 'youtube', NOW()),
('https://www.wikipedia.org', 'Wikipedia', NOW());

