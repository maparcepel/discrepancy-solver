
## Discrepancy solver

Aplicación desarrollada para corregir precios de productos que difieren de los de la web de El Corte Inglés, usando los archivos .csv enviados desde ECI con los precios correctos.

Se puede usar sólo después de validar que toda la información contenida en el archivo de discrepancias es correcta, es decir, que los precios Web son los correctos y no los de GBY.

El cálculo del PUM se hace a partir de la descripción del producto (datos del contenido y unidades de medida), la cual no está pensada para este objetivo, por lo que puede que algunos PUM no puedan calcularse. Por eso usar esta aplicación sólo cuando no se puedan corregir las discrepancias usando los feeds recibidos, por ejemplo cuando se recibieron hace tiempo.

- Crear la base de datos discrepancy-solver y ejecutar migraciones

- Guardar el archivo de discrepancias en storage/app/public con nombre discrepancias_Precios_ES.csv

- Ejecutar el endpoint http://127.0.0.1:8000/import-discrepances (GET) para guardar los datos de discrepancias_Precios_ES.csv en la tabla discrepances

- Ejecutar el endpoint http://127.0.0.1:8000/get-uuid-and-description-query (GET) que devuelve una query con las referencias y centros para ejecutar en producción.

- Ejecutar en producción la query obtenida en el paso anterior para obtener uuid y descripción (de aquí se obtendrán el contenido y la unidad de medida). La respuesta se debe guardar como uuidAndDescription.csv en storage/app/public.

- Ejecutar el endpoint http://127.0.0.1:8000/import-uuid-and-description (GET) que importará el archivo uuidAndDescription.csv, guardará en la tabla references uuid y descripción.

- Ejecutar el endpoint http://127.0.0.1:8000/create-feeds (GET) para calcular el pum y obtener los feeds. Se creará la carpeta con nombre (fecha)-prices dentro de storage/app/public .

- Copiar dentro de la carpeta creada el archivo product.json.schema que se encuentra en la carpeta public.

- Cargar la carpeta en Azure Storage para que se cargen los precios corregidos