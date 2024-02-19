# ToDo - Gestor de Tareas con PHP

Este es un sencillo gestor de tareas desarrollado en PHP, creado como parte de mi ejercicio durante mis estudios en Desarrollo de Aplicaciones Web (DAW). Este gestor te permite organizar y realizar un seguimiento de tus tareas pendientes, con la característica adicional de utilizar PHPMailer para enviar recordatorios por correo electrónico.

## Funcionalidades Destacadas:

- **Gestión de Tareas:** Agrega, edita y elimina tareas fácilmente.
  
- **Recordatorios por Correo Electrónico:** La integración de PHPMailer permite enviar recordatorios por correo electrónico para tareas pendientes.

## Instalación:

1. Clona este repositorio:

   ```bash
   git clone https://github.com/tu_usuario/todo-php.git
   cd todo-php
   ```

2. Configura tu entorno PHP y la base de datos.

3. Importa el archivo SQL proporcionado en el archivo `create.sql` para crear la estructura de la base de datos.

4. Configura las credenciales de correo electrónico en `EmailConfig.php` para habilitar la funcionalidad de recordatorios.

## Uso:

1. Registra tus tareas pendientes.
2. Edita o elimina tareas cuando sea necesario.
3. Recibe recordatorios por correo electrónico configurando la fecha y hora de vencimiento de la tarea.

## Tecnologías Utilizadas:

- PHP
- MariaDB (o tu base de datos preferida)
- PHPMailer

## Nota:

Este proyecto fue creado con fines educativos durante mi tiempo de estudio en DAW. Puede requerir ajustes y mejoras para adaptarse a entornos de producción.

¡Disfruta gestionando tus tareas con ToDo en PHP! 📅✉️
