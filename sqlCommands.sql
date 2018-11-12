CREATE DATABASE carpark_db;

\c carpark_db;


create table table_carpark
(
	carpark_id varchar not null,
	vehicle_type varchar not null,
	capacity int not null,
	space_type varchar,
	open_time int,
	close_time int,
	fare real not null,
	CONSTRAINT vehicle_type_carpark_id_chk PRIMARY KEY (vehicle_type,carpark_id),
	CONSTRAINT chk_vehicle_type CHECK (vehicle_type in ('H','C','M','B'))
	);


create table table_carpark_access
(
	carpark_id varchar not null,
	user_type varchar not null,
	CONSTRAINT user_type_carpark_id_chk PRIMARY KEY (user_type,carpark_id),
	CONSTRAINT chk_user_type CHECK (user_type in ('admin', 'vip', 'professor', 'student', 'staff', 'visitor'))
	);



create table table_carpark_location
(
	carpark_id varchar not null,
	latitude Decimal(9,6) not null,
	longitude Decimal(9,6) not null,
	gate_id varchar not null,
	active_status Boolean not null DEFAULT true,
	zone varchar,
	CONSTRAINT gate_carpark PRIMARY KEY (gate_id,carpark_id)
	);

create table table_filled_slots_current
(
	vehicle_id varchar NOT NULL PRIMARY KEY,
	carpark_id varchar NOT NULL,
	in_time timestamp NOT NULL,
	CONSTRAINT vehicle_slot_current FOREIGN KEY (vehicle_id)
	REFERENCES table_vehicle(vehicle_id) ON DELETE CASCADE ON UPDATE CASCADE
	);

create table table_landmarks
(
	name varchar NOT NULL PRIMARY KEY,
	latitude Decimal(9,6) not null,
	longitude Decimal(9,6) not null
	);

create table table_filled_slots_past
(
	vehicle_id varchar not null,
	carpark_id varchar not null,
	in_time timestamp not null,
	out_time timestamp not null,
	charge real,
	payment_type varchar,
	CONSTRAINT vehicle_slot_past FOREIGN KEY (vehicle_id)
	REFERENCES table_vehicle(vehicle_id) ON DELETE CASCADE ON UPDATE CASCADE
	);

create table table_vehicle
(
	vehicle_id varchar PRIMARY KEY not null,
	vehicle_type varchar not null,
	vehicle_number varchar not null,
	user_id varchar,
	CONSTRAINT chk_vehicle_type_1 CHECK (vehicle_type in ('H','C','M','B'))
	);

create table table_user
(
	user_id varchar PRIMARY KEY not null,
	user_password varchar not null,
	user_type varchar not null,
	CONSTRAINT chk_user_type CHECK (user_type in ('admin', 'vip', 'professor', 'student', 'staff', 'visitor'))
	);





INSERT INTO table_carpark VALUES('cid1','H',10,'openAir',0,24,32);
INSERT INTO table_carpark VALUES('cid2','H',10,'openAir',0,24,23);
INSERT INTO table_carpark VALUES('cid3','C',100,'openAir',0,24,30);
INSERT INTO table_carpark VALUES('cid1','C',100,'openAir',0,24,27);
INSERT INTO table_carpark VALUES('cid2','C',10,'openAir',0,24,10);

INSERT INTO table_user VALUES('ayush','ayush_pass','student');
INSERT INTO table_vehicle VALUES('ayush_car','C','101','ayush');


INSERT INTO table_carpark_location VALUES('cid1',1.353797, 103.686798,'A',true,'ABCD');
INSERT INTO table_carpark_location VALUES('cid2',1.349926, 103.683772,'A',true,'ABCD');
INSERT INTO table_carpark_location VALUES('cid3',1.347314, 103.677070,'A',true,'ABCD');

INSERT INTO table_carpark_access VALUES('cid1','student');
INSERT INTO table_carpark_access VALUES('cid2','student');
INSERT INTO table_carpark_access VALUES('cid3','student');


