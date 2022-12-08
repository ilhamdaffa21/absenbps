<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

abstract class BaseController extends Controller
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var CLIRequest|IncomingRequest
	 */
	protected $request;
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['tanggal_helper'];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		$this->helpers = array_merge($this->helpers, ['setting', 'form']);

		$this->helpers = array_merge($this->helpers, ['setting']);

		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();

		// set username and role to session
		if (auth()->loggedIn()) {
			$this->session = \Config\Services::session();
			$users = model('UserModel');
			$role = (auth()->user()->getGroups()[0]);
			$id = (session()->get()['user']['id']);
			$username = ($users->findById($id)->toArray()['username']);
			$userdata = [
				'username' => $username,
				'role' => $role,
			];
			$this->session->set($userdata);
		} //*

		if (date('D') != 'Sun' && date('D') != 'Sat') {
			$this->absensi_harian();
		} else {
			$this->log_to_console('Hari ini adalah hari libur');
		}

		header('refresh: 7200');
		$this->log_to_console('Telah Refresh Pada Jam: ' . date('H:i:s'));
	}

	function log_to_console($data)
	{
		if (is_array($data) || is_object($data)) {
			echo ("<script>console.log('" . json_encode($data) . "');</script>");
		} else {
			echo ("<script>console.log('" . $data . "');</script>");
		}
	}

	private function absensi_harian()
	{
		$date = date('Y-m-d');
		$jam = date('H:i:s');
		if ($jam >= '18:00:00' && $jam <= '23:59:00') {
			$pesertaModel = model('PesertaMagangModel');
			$scanModel = model('ScanModel');
			$presensiModel = model('PresensiModel');
			$peserta = $pesertaModel->findAll();
			$nim = [];
			foreach ($peserta as $p) {
				$nim[] = $p['nim'];
			}
			$this->log_to_console('Pada Tanggal: ' . $date);
			foreach ($nim as $n) {
				$cek_absen = $scanModel->cek_kehadiran($n, $date);
				if ($cek_absen) {
					if ($cek_absen->jam_keluar == '00:00:00' && $cek_absen->id_status == 1) {
						$id_presensi = $cek_absen->id;
						$data = [
							'id_kehadiran' => 4,
							'id_status' => 3,
							'keterangan' => 'Tidak Absen Pulang',
						];
						$presensiModel->update($id_presensi, $data) ? $this->log_to_console('Berhasil') : $this->log_to_console('Gagal');
						$this->log_to_console('NIM : ' . $n . ' | Status : Belum Absen Keluar');
					} else {
						$this->log_to_console('NIM : ' . $n . ' | Status : Sudah Absen');
					}
				} else {
					$data = [
						'id_kehadiran' => 4,
						'id_status' => 3,
						'nim' => $n,
						'tanggal' => $date,
						'jam_masuk' => '00:00:00',
						'jam_keluar' => '00:00:00',
						'keterangan' => 'Tidak Ada Keterangan',
					];
					$presensiModel->insert($data) ? $this->log_to_console('Berhasil') : $this->log_to_console('Gagal');
					$this->log_to_console('NIM : ' . $n . ' | Status : Belum Absen');
				}
			}
		} else {
			$this->log_to_console('Belum Waktu Tutup Presensi');
		}
	}
}
