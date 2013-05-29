CREATE TABLE user_login_password_change
(
    request_key VARCHAR(32) NOT NULL,
    token VARCHAR(32) NOT NULL,
    user_id INT(11) NOT NULL,
    PRIMARY KEY(request_key),
    UNIQUE(user_id)
) ENGINE=InnoDB;

CREATE TABLE user_login_attempts
(
  id INT(11) NOT NULL,
  user_id INT(11) NOT NULL,
  request_time DATETIME NOT NULL,
  ip_address VARCHAR(255) NOT NULL,
  PRIMARY KEY(id)
) ENGINE=InnoDB;