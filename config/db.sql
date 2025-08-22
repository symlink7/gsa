CREATE TABLE IF NOT EXISTS users(
  user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY, 
  user_first_name VARCHAR(24) COLLATE utf8_bin NOT NULL, 
  user_last_name VARCHAR(24) COLLATE utf8_bin NOT NULL, 
  user_phone VARCHAR(24) NOT NULL,
  user_email VARCHAR(128) NOT NULL,
  user_password VARCHAR(128) NOT NULL,
  user_role set('A', 'M', 'S', 'D') DEFAULT 'M'
) AUTO_INCREMENT=10001;

INSERT INTO users(
  user_first_name, 
  user_last_name, 
  user_email, 
  user_password,
  user_role)
VALUES('Elena', 'Genova', 'symlink7@gmail.com',
  md5('pass4ely'), 'A');
  
create table if not exists user_logins(
  user_login_id int AUTO_INCREMENT NOT NULL, 
  user_id int NOT NULL, 
  user_login_time DATETIME NOT NULL,
  user_login_ip VARCHAR(15) NOT NULL,
  PRIMARY KEY (user_login_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
) AUTO_INCREMENT=10001;  

CREATE TABLE IF NOT EXISTS user_actions(
  user_action_id INT AUTO_INCREMENT NOT NULL, 
  user_id INT NOT NULL, 
  user_action_descr VARCHAR(255) COLLATE utf8_bin, 
  user_action_time DATETIME NOT NULL, 
  user_action_ip VARCHAR(15) NOT NULL,
  PRIMARY KEY (user_action_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
) AUTO_INCREMENT=10001;


