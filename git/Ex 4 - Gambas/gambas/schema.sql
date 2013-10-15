use gambas;

create table PlacementDB
(
	rollno varchar(12) primary key,
	name varchar(30) not null,
	dob varchar(15),
	gender char,
	address varchar(200),
	mobile varchar(15),
	cgpa varchar(10),
	arrears varchar(2)
);
