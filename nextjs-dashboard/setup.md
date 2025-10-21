tôi có 2 source laravel-api và nextjs-dashboard, tôi muốn đồng bộ route của api ở cả 2 source. tôi dự định laravel-api xuất openApi swagger, sau đó nextjs-dashboard tự động sinh type+endpoint, import chỗ route api này để xử dụng phía nextjs-dashboard. Cách này có chuẩn đồng bộ api không ? nếu có thực hiện nó cho tôi
//////////////////

- Phase 1: Initial env (v)
	Required : Next.js with TypeScript, Tailwind, Shadcn UI, Redux-Saga, Axios, and next-themes

	- Khởi Tạo Next.js:
	Chạy: ```npx create-next-app@latest my-dashboard --typescript --tailwind --eslint --app```.

	- Cài Đặt Dependencies
	+ Asyn await
	```
	npm i @reduxjs/toolkit react-redux redux-saga axios lucide-react next-themes
	npm i -D @types/node
	```

	- Shadcn UI: Chạy ```npx shadcn-ui@latest init`` (chọn TypeScript, default theme). Add components cần: 
	```npx shadcn-ui@latest add button input card table dialog form avatar```.
- Phase 2: Define stucture và core page (v)
	+ Tạo themeprovider, layout master, theme color 
 		/ header
		/ sidebar
		/ footer
	+ Tạo page
		/ Home, category, .....
- Phase 3: Integrate API Calls with Laravel Backend
	+ Setup đồng bộ route api và path call api front-end (x)
	+ Setup common (v)
		/ API: path api, call api type method, handle error api, handle success api, encode, decode, set access token, set header, auth beaver, set payload body
		/ Message: 
		/ Const:
		/ Validation:

	+ API client (axios) + env config + proxy dev để FE gọi api laravel (v)
	+ setup redux & saga fetching (v)
	+ Success|Error handling response (v)
	+ Setup auth
- Phase 4: Implement core feature
	+ Dark mode
	+ Reponsive web
	+ Lazy load for media
	+ Basic SEO
	+ Search + pagination
	+ Validation
	+ Error handling & feedback UI (toast, skeletons, error boundaries).
	+ Bookmark/favorite (localStorage for guest, API for logged-in).
	+ Lazy-load images/videos, responsive images (next/image).
- Phase 5: Testing, optimize, dockerization
	+ Testing
	+ Optimize: minify, monitor performance SEo & lazy loading
	+ Docker setup
	+ Docker Compose dev setup chạy Next và Laravel cùng nhau, CORS/proxy config.