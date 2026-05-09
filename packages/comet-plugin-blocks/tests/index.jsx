import './setup.js';
import 'dashicons/dashicons.css';
import '../vendor/doubleedesign/comet-components-core/dist/dist.css';
import '@wordpress/block-editor/build-style/style.css';
import '@wordpress/components/build-style/style.css';
import { createRoot } from '@wordpress/element';
import { App } from './app.jsx';
import './app.css';

createRoot(document.getElementById('root')).render(<App/>);
