CREATE DATABASE books;

CREATE TABLE accounting_in (
    ID int(11),
    Date date,
    Supplier varchar(255),
    Reference int(11),
    Total decimal(11,2)
);

CREATE TABLE accounting_out (
    ID int(11),
    DATE date,
    Supplier varchar(255),
    Total decimal(11,2),
    Type varchar(255)
);

CREATE TABLE balances (
    ID int(11),
    TYPE varchar(255),
    Date date,
    Balance decimal(11,2)
);

CREATE TABLE petty_cash_id (
    ReferenceID int(11),
    Date date,
    Item varchar(255),
    Total decimal(11,2)
);

CREATE TABLE petty_cash_type (
    ID int(11),
    ReferenceID int(11),
    Type varchar(255),
    Total decimal(11,2)
);

CREATE TABLE user {
    ID int(11),
    username varchar(255),
    firstname varchar(255),
    lastname varchar(255),
    password varchar(255),
    email varchar(255)
}