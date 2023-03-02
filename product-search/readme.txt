
Developed as response to code challenge.

Built with laravel framework and using mysql as storage for the interim data.
Choose this as allows for a better range of future enhancements and features.
(If high performance were the key priority then would choose a simple single script
to read file contents and provide product list from in memory array)

Solution has back-end and front-end elements:
- Provides an import endpoint for retrieving the excel sheet,
  this means the excel sheet can continue to be the master data source.
  (could be done also via auto upload from storage location on regular basis)
- Provides a product listing endpoint with filter parameters
- Provides a single webpage using in-page React
  (ideally this would be a separate SPA using components)
- Other endpoints are available (see postman file)
- Includes test scripts for back-end

Running at http://pbnc.mywire.org:12345/

Regards,
Pierce Beckett
