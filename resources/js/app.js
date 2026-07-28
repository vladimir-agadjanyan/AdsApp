import '../scss/app.scss';

import 'bootstrap-icons/font/bootstrap-icons.css';
import 'leaflet/dist/leaflet.css';

import './map';

import Chart from 'chart.js/auto';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

Livewire.start();

window.Chart = Chart;