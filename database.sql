CREATE DATABASE railway_reservation;
USE railway_reservation;

CREATE TABLE trains (
    train_number VARCHAR(10) PRIMARY KEY,
    train_name VARCHAR(100),
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

CREATE TABLE reservations (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    train_number VARCHAR(10),
    seat_preference VARCHAR(10),
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (train_number) REFERENCES trains(train_number)
);

-- Insert some initial data
INSERT INTO trains (train_number, train_name, total_seats, available_seats) 
VALUES 
('12345', 'Express Train', 100, 100),
('67890', 'Superfast Train', 200, 200);

INSERT INTO reservations (name, train_number, seat_preference) 
VALUES 
('John Doe', '12345', 'Window'),
('Alice Smith', '67890', 'Aisle');
