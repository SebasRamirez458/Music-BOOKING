# Music-BOOKING

db schema:
 
CREATE TABLE Usuarios ( 
    user_id SERIAL PRIMARY KEY, 
    nombre VARCHAR(100) NOT NULL, 
    email VARCHAR(100) UNIQUE NOT NULL, 
    password VARCHAR(255) NOT NULL, 
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
); 
 
 
CREATE TABLE Bandas ( 
    band_id SERIAL PRIMARY KEY, 
    user_id INT NOT NULL REFERENCES Usuarios(user_id), 
    nombre_banda VARCHAR(100) UNIQUE NOT NULL, 
    num_integrantes INT 
); 
 
 
CREATE TABLE Salas ( 
    sala_id SERIAL PRIMARY KEY, 
    nombre_sala VARCHAR(100) NOT NULL, 
    descripcion TEXT, 
    precio_hora DECIMAL(10, 2) NOT NULL, 
    tipo_sala VARCHAR(50) 
); 
 
 
CREATE TABLE Equipos ( 
    equipo_id SERIAL PRIMARY KEY, 
    nombre_equipo VARCHAR(100) NOT NULL, 
    descripcion TEXT, 
    precio_dia DECIMAL(10, 2), 
    categoria VARCHAR(50), 
    disponible_prestamo BOOLEAN DEFAULT TRUE 
); 
 
 
CREATE TABLE Reservas ( 
    reserva_id SERIAL PRIMARY KEY, 
    band_id INT NOT NULL REFERENCES Bandas(band_id), 
    sala_id INT NOT NULL REFERENCES Salas(sala_id), 
    fecha_inicio TIMESTAMP NOT NULL, 
    duracion_horas INT NOT NULL, 
    fecha_fin TIMESTAMP,  -- Changed to a regular column 
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    estado_pago VARCHAR(20) DEFAULT 'Pendiente', 
    total_reserva DECIMAL(10, 2) 
); 
 
 
CREATE TABLE Prestamos ( 
    prestamo_id SERIAL PRIMARY KEY, 
    band_id INT NOT NULL REFERENCES Bandas(band_id), 
    fecha_inicio_prestamo TIMESTAMP NOT NULL, 
    fecha_fin_prestamo TIMESTAMP NOT NULL, 
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    estado_devolucion BOOLEAN DEFAULT FALSE, 
    total_prestamo DECIMAL(10, 2) 
); 
 
 
CREATE TABLE Reserva_Equipos ( 
    reserva_equipo_id SERIAL PRIMARY KEY, 
    reserva_id INT NOT NULL REFERENCES Reservas(reserva_id), 
    equipo_id INT NOT NULL REFERENCES Equipos(equipo_id) 
); 
 
 
CREATE TABLE Prestamo_Equipos ( 
prestamo_equipo_id SERIAL PRIMARY KEY, 
prestamo_id INT NOT NULL REFERENCES Prestamos(prestamo_id), 
equipo_id INT NOT NULL REFERENCES Equipos(equipo_id), 
precio_unitario_prestamo DECIMAL(10, 2) 
);