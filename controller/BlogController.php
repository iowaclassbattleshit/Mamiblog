<?php
require_once '../repository/BlogRepository.php';

class BlogController
{
    public function index()
    {
        $blogRepository = new BlogRepository();

        $view = new View('blog_index');

        $view->title = 'Blog';
        $view->heading = 'Blog';
        $view->users = $blogRepository->readAll();
        $view->display();
    }
    public function create()
    {
        $view = new View('blog_create');
        $view->title = 'Submit Image';
        $view->heading = 'Submit Image';
        $view->display();

    }
    public function doCreate()
    {
        if ($_POST['send']) {
            $destination = "images/uploads";
            $picture_array = $_FILES['picture'];
            $picture = $destination.'/';
            $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);



            $title = $_POST['title'];
            $date = date("Y.m.d");
            $comments = null;

            $blogRepository = new BlogRepository();

            $insertId = $blogRepository->upload($picture, $title, $date, $comments);
            if($insertId > 0)
            {
                $dst = $picture.$insertId.'.'.$ext;

                if (move_uploaded_file($picture_array['tmp_name'], $dst)) {
                    $blogRepository->update_picture($insertId, $dst);
                }
            }

        }
    }

}