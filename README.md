
## Discrepancy solver

- Crear la base de datos discrepancy-solver y ejecutar migraciones
- Copiar el archivo discrepancias_Precios_ES.csv en la carpeta storage/app/public
- En Postman ejecutar el endpoint import-discrepances (GET) para volcar los datos del .csv a la base de datos
- En Postman ejecutar el endpoint create-feeds (GET) para obtener los feeds. Se creará la carpeta con nombre (fecha)-prices/ dentro de public. Copiar en la carpeta creada el archivo product.json.schema que se encuentra en la carpeta public
