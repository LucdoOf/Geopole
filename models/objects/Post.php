<?php

require_once(dirname(__DIR__) . "/objects/Model.php");

class Post extends Model {

    use Timeable;

    const STORAGE = "posts";
    const CATEGORIES_STORAGE = "posts_categories";
    const RATING_STORAGE = "users_posts_rating";
    const COLUMNS =  [
        "id" => true,
        "title" => false,
        "description" => false,
        "content" => false,
        "author" => false,
        "image" => false,
        "creation_date" => true,
        "viewed" => true,
        "slug" => false
    ];

	private $_title;
	private $_description;
	private $_content;
	private $_author;
	private $_image;
	private $_creationDate;
	private $_viewed;
	private $_slug;

    /**
     * Représente un article
     * Post constructor.
     * @param $data
     */
	public function __construct($data = []){
		parent::__construct($data);
	}


    /**
     * Retourne le rating moyen de l'article
     * @return float
     */
	public function getRating(){
        return (float)SQL::select($this::RATING_STORAGE, ["post_id" => $this->getId()], null, null, null, [],
            ["AVG(rating) AS counter"])->fetch()["counter"];
    }

    /**
     * Supprime toute les catégories de l'article
     */
	public function clearCategories(){
	    SQL::delete($this::CATEGORIES_STORAGE, ["post_id" => $this->getId()]);
    }

    /**
     * Ajoute une catégorie à l'article
     * @param $category Category
     */
	public function addCategory($category){
        SQL::insert($this::CATEGORIES_STORAGE, ["post_id" => $this->getId(), "category_id" => $category->getId()]);
    }

    /**
     * Retourne la liste des catégories de l'article
     * @return Category[]
     */
	public function getCategories(){
	    return Category::getAll(["post_id" => $this->getId()], null, null, null, [$this::CATEGORIES_STORAGE => ["categories.id", "posts_categories.category_id"]]);
    }

    /**
     * Retourne la liste des commentaires parents ou sans enfants de l'article
     * @return Comment[]
     */
	public function getHeadingComments(){
	    return Comment::getAll(["post_id" => $this->getId(), "parent_id" => -1]);
    }

    /**
     * Récupère les derniers posts créés
     * @param $number
     * @param null $category
     * @param int $offset
     * @return Post[]
     */
    public static function getLastCreated($number, $category = null, $offset = 0)
    {
        $where = !is_null($category) ? ["posts_categories.category_id" => $category->getId()] : [];
        return static::getAll($where, "TIMESTAMP(creation_date) DESC", $number, $offset, ["posts_categories" => ["posts_categories.post_id", Post::STORAGE . ".id"]]);
    }

    /**
     * Compte en fonction d'une catégorie
     * @param null $category
     * @return mixed
     */
    public static function countByCategory($category = null){
        return is_null($category) ? parent::count() : parent::count(["posts_categories.category_id" => $category->getId()], ["posts_categories" => ["posts_categories.post_id", "posts.id"]]);
    }

    /**
     * Récupère un post en fonction de son slug
     * @param $slug
     * @return Post
     */
	public static function getBySlug($slug){
	    return static::select(["slug" => $slug]);
    }

    /**
     * Retourne la liste des posts les plus lus
     * @param $top int
     * @return Post[]
     */
    public static function getMostRead($top){
        return static::getAll([], "viewed DESC", $top);
    }

    /**
     * Ajoute un commentaire a l'article
     * @param $author
     * @param $content
     */
    public function comment($author, $content){
	    $comment = new Comment(["author" => $author, "content" => $content, "post_id" => $this->getId()]);
	    $comment->save();
    }

	protected function setId($id){
		$id = (int) $id;
		if($id >= 0){
			$this->_id = $id;
		}
	}

	public function setTitle($title){
		if(is_string($title) && !empty($title)){
			$this->_title = $title;
		} else {
			$this->_title = "Titre null";
		}
	}

	public function setDescription($description){
		if(is_string($description) && !empty($description)){
			$this->_description = htmlspecialchars($description);
		} else {
			$this->_description = "Description null";
		}
	}

	public function setContent($content){
		if(is_string($content) && !empty($content)){
			$this->_content = $content;
		} else {
			$this->_content = "Article vide";
		}
	}

	protected function setAuthor($author){
        $author = (int)$author;
        if ($author >= 0) {
            $this->_author = $author;
        }
    }

    public function setSlug($slug){
        $this->_slug = $slug;
    }

	public function setImage($image){
		if(is_string($image)){
			$this->_image = $image;
		}
	}

	protected function setCreation_date($creationDate){
		$this->_creationDate = $creationDate;
	}

	public function setViewed($viewed){
		$viewed = (int) $viewed;
		if($viewed >= 0){
			$this->_viewed = $viewed;
		}
	}

	public function getId(){ return $this->_id; }
	public function getTitle(){ return $this->_title; }
	public function getDescription(){ return $this->_description; }
	public function getContent(){ return $this->_content; }
	public function getAuthor(){ return $this->_author; }
	public function getImage(){ return $this->_image; }
	public function getCreation_date(){ return $this->_creationDate; }
	public function getViewed(){ return $this->_viewed; }
	public function getSlug(){ return $this->_slug; }
	
}
