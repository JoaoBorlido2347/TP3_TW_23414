
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('normal', 'admin') NOT NULL,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  birthday DATE,
  bio TEXT,
  address VARCHAR(255),
  extra_field VARCHAR(100)
);

INSERT INTO usuarios (username, email, password, role, first_name, last_name, birthday, bio, address, extra_field) VALUES
  ('admin', 'admin@mail.com', 'admin123', 'admin', 'Admin', 'Not', '1990-01-01', 'Administrator account', '123 Admin St, Admin City', 'Extra info'),
  ('usuario1', 'usuario1@mail.com', 'senha123', 'normal', 'João', 'Silva', '1985-05-10', 'Usuário comum', 'Rua das Flores, 123', 'Outro campo'),
  ('usuario2', 'qwe@mail.com', 'senha456', 'normal', 'Maria', 'Souza', '1992-11-15', 'Outro usuário', 'Av. Principal, 456', 'Alguma informação');


  CREATE TABLE animal (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  especie VARCHAR(50) NOT NULL,
  vacinado BOOLEAN NOT NULL DEFAULT false,
  tem_chip BOOLEAN NOT NULL DEFAULT false,
  data_adocao DATE,
  id_usuario INT NOT NULL,
  imagem_url VARCHAR(255),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);