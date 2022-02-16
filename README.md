# EcoFincaBackEnd
Backend del proyecto de tésis con el Framework Laravel 8

Para el despliegue del backend se utilizará Heroku junto a la documentación proporcionada para el despliegue de un proyecto Laravel en el siguiente link: https://devcenter.heroku.com/articles/getting-started-with-laravel
# Requisitos
* Crear una cuenta en Heroku
* Tener instalado Git 
* Instalar el CLI de Heroku como se indica en la documentacion
# Despliegue
1) Se recomienda crear una nueva rama para aplicar los cambios que se realizaran y evitar tocar la rama master.
2) Una vez creada la rama creamos un archivo Procfile en la raiz del proyecto con el siguiente contenido:
![image](https://user-images.githubusercontent.com/38251240/151827775-004094aa-712b-49c9-bfd9-4b9b51848a83.png)
3) Subimos los cambios realizados a GitHub
4) Ahora creamos el proyecto en Heroku con el siguiente comando: heroku create nombreapp, en nombreapp va el nombre que le queremos dar a nuestra aplicación en Heroku que en este caso sera backecofinca. Si todo salio correctamente se nos dará la URL del proyecto como se muestra a continuación:
![image](https://user-images.githubusercontent.com/38251240/151828895-34e68c56-744e-473c-b5cd-4b8b353c381f.png)
5) Luego empezamos las configuraciones de las variables de entorno empezando con la APP_KEY con el siguinte comando:
![image](https://user-images.githubusercontent.com/38251240/151829178-ef37133e-b34c-4f93-a494-76788c4be6f6.png)
6) Para las restantes variables de entorno utilizamos la configuracion del proyecto y con figuramos las variables que se muestran a continuación:

![image](https://user-images.githubusercontent.com/38251240/151829687-a8cb58c5-b9be-4104-a40d-8b5676e7897a.png)
![image](https://user-images.githubusercontent.com/38251240/151829780-b903f835-d194-4cfe-b4ea-74b5d0b185cc.png)

7) Para las variables de la base de datos creamos un recurso de base de datos utilizando Postgresql ya que es de uso gratuito:
![image](https://user-images.githubusercontent.com/38251240/151830086-592379de-0abf-44ca-a5f1-73cfc6427fc8.png)
![image](https://user-images.githubusercontent.com/38251240/151830165-cd0a8123-f642-44b0-bb74-e7de1d63257f.png)
![image](https://user-images.githubusercontent.com/38251240/151830331-ac2f9688-dc21-4422-ad3d-65941bfe04d8.png)
8) Cuando se haya finalizado la configuración de las variables de entorno ejecutamos el comando: heroku run bash para abrir una linea de comando que nos permita ejecutar comandos php y ejecutar las migraciones:
![image](https://user-images.githubusercontent.com/38251240/151830820-6218c6b3-aea2-4660-b06e-3ebac6a5337d.png)
9) Finalmente la aplicacion queda lista para su consumo.
# URL de la aplicación:
https://backecofinca.herokuapp.com/
