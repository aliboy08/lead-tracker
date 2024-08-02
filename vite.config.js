import { v4wp } from './v4wp/v4wp';
import mkcert from 'vite-plugin-mkcert';

export default {
	server: { https: true },
	plugins: [
		v4wp({
			input: {
				admin: 'src/admin/admin.js',
			},
			outDir: 'dist',
		}),
		mkcert(),
	],
	resolve: {
		alias: {
			src: '/src',
		},
	},
};
