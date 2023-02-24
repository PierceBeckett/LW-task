<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

		<script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
		<script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
		<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" integrity="sha512-EZLkOqwILORob+p0BXZc+Vm3RgJBOe1Iq/0fiI7r/wJgzOFZMlsqTa29UEl6v6U6gsV4uIpsNZoV32YZqrCRCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

		<style>
			label {
				width: 60px;
			}
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
			.rammer {
				background-color: gray;
			}
		</style>
    </head>
    <body class="antialiased">
	
		<div class="">
			<h2>Pierce Beckett</h2>
			<p>Basic web page for perusing the elements of the task challenge</p>
		</div>
		
		<div id="root" ></div>

		<script type="text/babel">
		function LWForm() {

			// init vars
			const be_server = 'http://pbnc.mywire.org:12345/api/';
			const [search, setSearch] = React.useState({});
			const [ramCheck, setRamCheck] = React.useState([]);
			const [ram_opts, setRam] = React.useState([]);
			const [hdd_opts, setHdd] = React.useState([]);
			const [loc_opts, setLoc] = React.useState([]);
			const [products, setProducts] = React.useState([]);

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
				console.log(updatedRamState);
				setRamCheck(updatedRamState);
			};

			// load up the lookup lists
			React.useEffect(() => {
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
			}, []);

			// function for controls change
			// i.e. retrieve the products
			React.useEffect(() => {
				if (Object.keys(search).length > 0 ||
					ramCheck.length > 0
				) {
					let params = '?';
					for (let key in search) {
						if (search[key]) params += key+'='+search[key]+'&';
					};
					ramCheck.map((checked, id) => {
						if (checked) params += 'ram_id[]='+id+'&';
					});
					fetch(be_server+'product'+params, {"method" : 'GET'})
					.then((response) => response.json())
					.then((data) => {
						setProducts(data);
					})
				}
			}, [search, ramCheck]);

			return (
				<form>
					<div className="left">
					<label>Model
					<input
						name="model"
						type="text"
						value={search.model}
						onChange={handleChange}
						/>
					</label>

					<label className='rammer'>RAM<br />
						{ram_opts.length > 0 && ram_opts.map((option) => {
							return 	(
								<>
								<input name="ram_id" type="checkbox" key={option.id} value={option.id} label={option.value}
									id={'ramChk-'+option.id}
									isselected={ramCheck[option.id]}
									onChange={() => handleRamChange(option.id)}
								/>
								<label htmlFor={'ramChk-'+option.id}>{option.value}</label>
								</>
							);
						})}
					</label>

					<label>HDD
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
					</label>

					<label>Location
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
					</label>

					</div>
					<div className="right">
					<table>
						<thead>
						<tr>
							<th>Model</th>
							<th>Ram</th>
							<th>HDD</th>
							<th>Location</th>
							<th>Price</th>
						</tr>
						</thead>
						<tbody>
						{products.length > 0 && products.map((product) => {
							return (
								<tr key={product.id}>
									<td>{product.model}</td>
									<td>{product.ram}</td>
									<td>{product.hdd}</td>
									<td>{product.location}</td>
									<td>{product.currency+product.price}</td>
								</tr>
							);
						})}
						</tbody>
					</table>
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

	</body>
</html>
