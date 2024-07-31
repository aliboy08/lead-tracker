import { v4wp } from './vite/v4wp/v4wp';
import mkcert from 'vite-plugin-mkcert';

export default {
	server: { https: true },
	plugins: [
		v4wp({
			input: {
				// index: 'src/index.js',
				admin: 'src/admin/admin.js',
			},
			outDir: 'dist',
		}),
		mkcert(),
	],
	resolve: {
		alias: {
			src: '/src',
            css: '/src/css',
			js: '/src/js',
			assets: '/assets',
            images: '/assets/images',
		},
	},
};
