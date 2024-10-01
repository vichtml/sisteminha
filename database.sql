CREATE TABLE usuarios (
	usuarios_codigo int NOT NULL AUTO_INCREMENT,
	usuarios_email varchar(255) not null,
	usuarios_senha varchar(255) not null,
	usuarios_ativo int(1) default '1' not null,
	PRIMARY KEY (usuarios_codigo)
);

INSERT INTO usuarios VALUES(0,'professor@aula.com','7c4a8d09ca3762af61e59520943dc26494f8941b',1);

CREATE TABLE turmas (
	turmas_codigo int NOT NULL AUTO_INCREMENT,
	turmas_nome varchar(255) not null,
	turmas_usuario int not null,
	turmas_ativo int(1) default '1' not null,
	PRIMARY KEY (turmas_codigo),
    FOREIGN KEY (turmas_usuario) REFERENCES usuarios (usuarios_codigo)
);

INSERT INTO turmas VALUES(0,'A - Eng. de Computação',1,1);
INSERT INTO turmas VALUES(0,'B - Ciência da Computação',1,1);
INSERT INTO turmas VALUES(0,'C - Eng. de Software',1,1);

CREATE TABLE alunos (
	alunos_codigo int NOT NULL AUTO_INCREMENT,
	alunos_nome varchar(255) not null,
    alunos_sobrenome varchar(255) not null,
	alunos_nascimento date not null,
    alunos_turma int not null,
    alunos_nota_1 decimal(4, 2),
    alunos_nota_2 decimal(4, 2),
    alunos_nota_3 decimal(4, 2),
	alunos_ativo int(1) default '1' not null,    
	PRIMARY KEY (alunos_codigo),
    FOREIGN KEY (alunos_turma) REFERENCES turmas (turmas_codigo)
);