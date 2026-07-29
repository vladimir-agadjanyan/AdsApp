import '../scss/app.scss';

import 'bootstrap-icons/font/bootstrap-icons.css';
import 'leaflet/dist/leaflet.css';

import './map';

import Chart from 'chart.js/auto';
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Chart = Chart;
globalThis.Chart = Chart;

console.log('window.Chart', window.Chart);
console.log('globalThis.Chart', globalThis.Chart);

Livewire.start();