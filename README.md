#Colored balls distribution

##Algorithm description
1. Order the arrays that contain the colored balls from smallest to larges
2. Flatten to a 1 dimensional array
3. Start at index i (default is 0) and check if elements form index i to n(number of colors) have a maximum of 2 colors, if true make a group, if false increment the index by 1 and repeat this step
4. Remove the element from i to 2 from the flatten array
5. If array is not empty go to step 3

##Backend REST API made with laravel
Endpoint `/api/balls`

`POST /api/balls` has 2 parameter:
- `nrOfColors`: the n parameter in the test
- `json`: This is an optional parameter, if not specified a random distribution will be generated. If specified it must be in JSON format `{"red":1,"green":3}`


`GET /api/balls/id` has 1 parameter:
- `id`: the id from a successful POST to `/api/balls`

Response:
```{
	"hasError": false,
	"colors": {
		"blue": 6,
		"red": 2,
		"green": 4,
		"purple": 4
	},
	"groups": [
		["red", "red", "green", "green"],
		["green", "green", "purple", "purple"],
		["purple", "purple", "blue", "blue"],
		["blue", "blue", "blue", "blue"]
	],
	"id": 1
}
```
The code for the algorithm can be found in `app/BallsLogic.php`
