drop database my_db;
create database my_db;
use my_db;

insert into invoices(amount, invoice_number, status, created_at, due_date) VALUES (1, 'haha', 1, NOW(), NOW()); 