DROP DATABASE IF EXISTS iot_ravaocong;
CREATE DATABASE iot_ravaocong;
USE iot_ravaocong;

CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    name VARCHAR(50) NOT NULL,
    phone VARCHAR(50) NULL,
    money INT(11) NULL DEFAULT 0,
    access_token VARCHAR(50) NULL,
    gender ENUM('male', 'female') NOT NULL DEFAULT 'male',
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    money INT(11) NOT NULL,
    transaction_id VARCHAR(50) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE histories (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    money INT(11) NULL,
    type ENUM('in', 'out') NOT NULL DEFAULT 'in',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE transactions ADD CONSTRAINT fk_transactions_users FOREIGN KEY (user_id) REFERENCES users(id);
ALTER TABLE histories ADD CONSTRAINT fk_histories_users FOREIGN KEY (user_id) REFERENCES users(id);


INSERT INTO users (email, password, name, phone, money, access_token, gender, role) VALUES 
('admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Admin', '0123456789', '50000', '097986af9as6f9asf6asf6698fs6af986af986a', 'male','admin'),
('user@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Văn A', '0123456789', '10000', '515h1lk25h1lk5h12l5h125klh15l1h5lh', 'female', 'user');


INSERT INTO transactions (user_id, money, created_at) VALUES
(1, 10000, '2023-09-01 12:00:00'),
(1, 20000, '2023-09-01 13:00:00'),
(1, 30000, '2023-10-01 12:00:00'),
(1, 40000, '2023-10-01 12:00:00'),
(1, 50000, '2023-11-01 12:00:00');

INSERT INTO histories (user_id, money, type, created_at) VALUES
(1, '0','in', '2023-09-01 12:00:00'),
(1, '3000','out', '2023-09-01 14:00:00'),
(1, '0','in', '2023-10-01 12:00:00'),
(1, '3000','out', '2023-10-01 14:00:00'),
(1, '0','in', '2023-11-01 12:00:00');

select * from users;
select * from transactions;
select * from histories;


INSERT INTO histories (user_id, money, type, created_at) VALUES
(1, '10000','out', '2023-11-11 12:00:00'),

UPDATE users SET money = 100000 WHERE id = 1;