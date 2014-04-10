<?

require_once(dirname(__FILE__) . '/thumb/phpthumb.class.php');

class PImageResizer extends CApplicationComponent
{

	// конфа по
	public $thumbs;

	private $_phpThumb;

	public function init() {
		$this->_phpThumb = new phpThumb();

		$this->_phpThumb->setParameter('config_output_format', 'jpeg');
		$this->_phpThumb->setParameter('config_allow_src_above_docroot', true);
		$this->_phpThumb->setParameter('q', 95);
	}

	/**
	 * @param $src string - путь до изображения
	 * @param $dst string - путь до изображения
	 * @param $params array - массив с параметрами
	 * @return bool -
	 */
	public function resize($src, $dst, $params) {
		$this->_phpThumb->resetObject();

		$this->_phpThumb->setSourceFilename($src);
		foreach ($params as $key => $val) {
			$this->_phpThumb->setParameter($key, $val);
		}

		if ($this->_phpThumb->GenerateThumbnail()) {
			return $this->_phpThumb->RenderToFile($dst);
		}

		return false;
	}

	/**
	 * Генерит превью из картинки, ложит туда же, в туже папку.
	 * @param $src string - путь до изображения
	 * @param $params array - массив с параметрами
	 * @param string $suffix - суффикс в имени файла
	 */
	public function thumb($src, $params, $suffix = '_thumb') {
		$ext = CFileHelper::getExtension($src);
		//Yii::log('Resize file '.$src . ' - ext is '.$ext);

		$name = basename($src , '.'.$ext);
		$thumbPath = dirname($src) . DS . $name . $suffix . '.' . $ext;

		//CVarDumper::dump($thumbPath);

		if ($this->resize($src, $thumbPath, $params)) {
			return $thumbPath;
		}

		return false;
	}
}