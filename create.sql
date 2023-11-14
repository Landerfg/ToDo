DROP TABLE IF EXISTS ToDO;

CREATE  TABLE ToDo (
	id INT AUTO_INCREMENT PRIMARY KEY ,
	tarea VARCHAR(200),
	prioridad TINYINT CHECK (prioridad >= 1 AND prioridad <= 5), 
	correo VARCHAR(200) DEFAULT NULL,
	descripcion TEXT
);


-- Insertar tareas con datos inventados en la tabla ToDo
INSERT INTO ToDo (tarea, prioridad, correo, descripcion) VALUES ('Comprar leche', 2, 'usuario1@gmail.com', 'Ir al supermercado y comprar leche.');
INSERT INTO ToDo (tarea, prioridad, descripcion) VALUES ('Llamar a Juan', 3, 'Recordarle a Juan la reunión de mañana.');
INSERT INTO ToDo (tarea, prioridad, correo, descripcion) VALUES ('Pasear al perro', 1, 'usuario2@gmail.com', 'Sacar al perro a dar un paseo en el parque.');
INSERT INTO ToDo (tarea, prioridad, correo) VALUES ('Pagar facturas', 4, 'usuario3@gmail.com');
INSERT INTO ToDo (tarea, prioridad, descripcion) VALUES ('Estudiar para el examen', 5, 'Preparar el material de estudio.');
INSERT INTO ToDo (tarea, prioridad) VALUES ('Hacer ejercicio', 2);
INSERT INTO ToDo (tarea, prioridad, descripcion) VALUES ('Limpiar la casa', 3, 'Realizar una limpieza profunda.');
INSERT INTO ToDo (tarea, prioridad, correo, descripcion) VALUES ('Enviar informe', 4, 'usuario4@gmail.com', 'Enviar el informe mensual al jefe.');
INSERT INTO ToDo (tarea, prioridad) VALUES ('Hacer la lista de compras', 1);
INSERT INTO ToDo (tarea, prioridad, correo) VALUES ('Reparar la puerta', 5, 'usuario5@gmail.com');
INSERT INTO ToDo (tarea, prioridad, correo, descripcion) VALUES ('Comprar regalos de cumpleaños', 3, 'usuario6@gmail.com', 'Buscar regalos para el cumpleaños de María.');
INSERT INTO ToDo (tarea, prioridad) VALUES ('Revisar el coche', 2);
INSERT INTO ToDo (tarea, prioridad, descripcion) VALUES ('Preparar la cena', 1, 'Preparar una cena especial.');
INSERT INTO ToDo (tarea, prioridad, correo, descripcion) VALUES ('Reservar vuelos', 4, 'usuario7@gmail.com', 'Reservar vuelos para las vacaciones.');
INSERT INTO ToDo (tarea, prioridad, correo) VALUES ('Hacer la declaración de impuestos', 5, 'usuario8@gmail.com');
