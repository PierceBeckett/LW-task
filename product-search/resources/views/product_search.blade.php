<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>BP Beckett - Leaseweb challenge</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

		<script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
		<script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
		<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
		<script
			src="https://unpkg.com/@mui/material@5.0.0/umd/material-ui.development.js"
			crossorigin="anonymous"
			></script>
		<!-- Fonts to support Material Design -->
		<link
			rel="stylesheet"
			href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
			/>
		<!-- Icons to support Material Design -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />



		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" integrity="sha512-EZLkOqwILORob+p0BXZc+Vm3RgJBOe1Iq/0fiI7r/wJgzOFZMlsqTa29UEl6v6U6gsV4uIpsNZoV32YZqrCRCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

		<style>
			.left {
				float: left;
			}
			.right {
				float: right;
			}
			.footer {
				position: fixed;
				left: 0;
				bottom: 0;
				width: 100%;
				background-color: green;
				color: white;
				text-align: center;
			}
			.lightgray {
				background-color: lightgray;
			}
			.row {
				padding: 5px;
			}
			.header {
				background-color: green;
				font-weight: bold;
				color: white;
				padding: 5px;
			}
			div.container {
				width: 100%;
			}
			.aright {
				text-align: right;
			}
		</style>
    </head>
    <body className="antialiased">
	
		<div className="">
			<h2>Pierce Beckett</h2>
			<p>Basic web page for perusing the elements of the task challenge</p>
		</div>
		
		<div id="root" ></div>

		<script type="text/babel">

		function LWForm() {

			// init vars
			const be_server = 'http://127.0.0.1:12345/api/';
			const [search, setSearch] = React.useState({'storage_min' : 100, 'storage_max': 30000});
			const [ramCheck, setRamCheck] = React.useState([]);
			const [ram_opts, setRam] = React.useState([]);
			const [hdd_opts, setHdd] = React.useState([]);
			const [loc_opts, setLoc] = React.useState([]);
			const [products, setProducts] = React.useState([]);
  			const [uploadFile, setUploadFile] = React.useState();
			const [sort, setSort] = React.useState({'field':'model','dir':'asc'});

			const handleChange = (e) => {
				setSearch((prevState) => ({
				...prevState,
				[e.target.name]: e.target.value,
				}));
			};

			const handleRamChange = (position) => {
				const updatedRamState = ramCheck.map((item, index) =>
					index === position ? !item : item
				);
				setRamCheck(updatedRamState);
			};

			const handleUpload = (e) => {
				e.preventDefault();

				// prevent user interaction while uploading
				const modal = document.getElementById("uploadModal");
				modal.style.display = "block";

				let data = new FormData();
  				data.append("products", uploadFile);

				fetch(be_server+'product/import',
					{
						method: 'POST',
						body: data
					},
					true
				)
				.then((data) => data.json())
				.then((response) => {
					setUploadFile();
					if (response.success) {
						doLookups();
						alert('Upload succeeded. '+response.message);
						doSearch();
					} else {
						alert('Upload failed. '+response.message);
					}
					modal.style.display = "none";
				})
				.catch((error) => {
					setUploadFile();
					alert('Upload failed. '+error);
					modal.style.display = "none";
				});
			};

			// load up the lookup lists
			const doLookups = () => {
				fetch(be_server+'ram', {"method" : 'GET'})
					.then((response) => response.json())
					.then((data) => {
						setRam(data);
						let ramCheckBase = [];
						data.map((option) => {
							ramCheckBase[option.id] = false;
						});
						setRamCheck(ramCheckBase);
					});
				fetch(be_server+'hdd', {"method" : 'GET'})
					.then((response) => response.json())
					.then((data) => {
						setHdd(data);
					});
				fetch(be_server+'location', {"method" : 'GET'})
					.then((response) => response.json())
					.then((data) => {
						setLoc(data);
					});
			}
			// on page first load
			React.useEffect(() => {
				doLookups();
			}, []);

			// function for controls change
			// i.e. retrieve the products
			const doSearch = () => {
				const modal = document.getElementById("loadingModal");
				modal.style.display = "block";
				let params = '?';
					for (let key in search) {
						if (search[key]) params += key+'='+search[key]+'&';
					};
					ramCheck.map((checked, id) => {
						if (checked) params += 'ram_id[]='+id+'&';
					});
					params += 'sort_by='+sort.field+'&sort_dir='+sort.dir;
					fetch(be_server+'product'+params, {"method" : 'GET'})
					.then((response) => response.json())
					.then((data) => {
						setProducts(data);
						modal.style.display = "none";
					})
					.catch((error) => {
						alert('Failed to fetch results. '+error);
						modal.style.display = "none";
					})
			}
			React.useEffect(() => {
				if (Object.keys(search).length > 0 ||
					ramCheck.length > 0
				) {
					doSearch();
				}
			}, [search, ramCheck, sort]);

			const handleSort = (e) => {
				e.preventDefault();
				const dir = (e.target.id == sort.field) ? toggle(sort.dir) : sort.dir;
				setSort({'field' : e.target.id, 'dir' : dir});
			}

			const toggle = (dir) => {
				return (dir =='asc') ? 'desc' : 'asc';
			}

			return (
				<form>
				<div className="container">
				<div className='row'>
					<div className="three columns">
					<label>Model</label>
					<input
						name="model"
						type="text"
						value={search.model}
						onChange={handleChange}
						/>

					<label>Storage</label>
					<div>
						Min - <span>{search?.storage_min}</span>GB <input type="range" min="100" step="100" max="5000" value={search?.storage_min} id="storageMin"
						name="storage_min" onChange={handleChange} />
					</div>
					<div>
					Max - <span>{search?.storage_max/1000}</span>TB <input type="range" min="1000" step="100" max="30000" value={search?.storage_max} id="storageMax" 
						name="storage_max" onChange={handleChange} />
					</div>

					<label>RAM</label>
						<div className="container lightgray">
						<div className="row">
						{ram_opts.length > 0 && ram_opts.map((option) => {
							return 	(
								<div className="three columns" key={option.id}>
								<label htmlFor={'ramChk-'+option.id}>{option.value}</label>
								<input name="ram_id" type="checkbox" value={option.id} label={option.value}
									id={'ramChk-'+option.id}
									isselected={ramCheck[option.id] ? 'selected' : ''}
									onChange={() => handleRamChange(option.id)}
								/>
								</div>
							);
						})}
						</div>
						</div>

					<label>HDD</label>
					<select
						name="hdd_id"
						label="hdd"
						value={search?.hdd}
						onChange={handleChange}
						>
						<option value=''>Any</option>
						{hdd_opts.length > 0 && hdd_opts.map((option) => {
							return 	(
								<option key={option.id} value={option.id}>
								{option.value}
								</option>
							);
						})}
					</select>

					<label>Location</label>
					<select
						name="location_id"
						label="loc"
						value={search?.loc}
						onChange={handleChange}
						>
						<option value=''>Any</option>
						{loc_opts.length > 0 && loc_opts.map((option) => {
							return 	(
								<option key={option.id} value={option.id}>
								{option.value}
								</option>
							);
						})}
					</select>

					<label>Upload new data</label>
					{uploadFile?.name != '' || "Choose CSV/XLS"}
					<input type="file" name="products"
                		accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
						onClick={(e) => {
							e.target.value = "";
						}} // this is to clear value in case same file reselected after error
						onChange={(e) => {
							setUploadFile(e.target.files[0]);
						}}
						/>
					{uploadFile && (
						<button
							action="primary"
							onClick={(e) => {
								handleUpload(e);
							}}
						>{"Upload " + uploadFile?.name}</button>
					)}
					</div>

					<div className="nine columns">
					<div className='container'>
						<div className='row header'>
							<div className='twelve columns' id='model' onClick={handleSort}>Model</div>
							<div className='two columns'>Ram</div>
							<div className='two columns'>HDD</div>
							<div className='two columns aright' id='storage' onClick={handleSort}>Storage</div>
							<div className='three columns'>Location</div>
							<div className='two columns right aright' id='price' onClick={handleSort}>Price</div>
						</div>
						{products.length > 0 && products.map((product) => {
							return (
								<div className='row' key={product.id}>
									<div className='twelve columns lightgray'>{product.model}</div>
									<div className='two columns'>{product.ram}</div>
									<div className='two columns'>{product.hdd}</div>
									<div className='two columns aright'>
									{product.storage < 1000 && product.storage+'GB'}
									{product.storage >= 1000 && product.storage/1000+'TB'}
									</div>
									<div className='three columns'>{product.location}</div>
									<div className='two columns right aright'>{product.currency+product.price}</div>
								</div>
							);
						})}
					</div>
					</div>
					</div>
					</div>
				</form>
			);
		}

		const root = ReactDOM.createRoot(document.getElementById('root'));
		root.render(<LWForm />);
		</script>

		<div class="footer">
			<p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
		</div>

		<div id="uploadModal" class="modal">
			<div class="modal-content">
				<h2>Data is currently uploading ...</h2>
			</div>
		</div>
		<div id="loadingModal" class="modal">
			<div class="modal-content">
				<img src='/loading.gif' height='40px' />
			</div>
		</div>
		<style>
		/* The Modal (background) */
		.modal {
			display: none; /* Hidden by default */
			position: fixed; /* Stay in place */
			z-index: 1; /* Sit on top */
			left: 0;
			top: 0;
			width: 100%; /* Full width */
			height: 100%; /* Full height */
			overflow: auto; /* Enable scroll if needed */
			background-color: rgb(0,0,0); /* Fallback color */
			background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
		}

		/* Modal Content/Box */
		.modal-content {
			text-align: center;
			background-color: #fefefe;
			margin: 35% auto; /* 15% from the top and centered */
			padding: 20px;
			border: 1px solid #888;
			width: 80%; /* Could be more or less, depending on screen size */
		}
		</style>

	</body>
</html>
