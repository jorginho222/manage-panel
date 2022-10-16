<?php

namespace App\Http\Traits;

use App\Models\Product;
use App\Models\Supply;

trait OrderArticlesTrait 
{
    public $showNewArticleButton = true;
    public $enableActions = true;
    public $showArticleSelector = false;
    public $showQuantityPrice = false;
    public $showOptions = false;
    public $showArticleList = false;
    public $showActions = true;
    public $showUpdateButton = false;
    public $showFinalizeOrderButton = false;
    public $enableFinalizeOrderButton = true;

    public $articles;
    public $articleId;
    public $articleTitle;
    public $quantity = 1;
    public $price;

    public $articleList = [];
    public $articleListKey;

    public $partialPrice;
    public $orderData = [];
    public $totalArticlesPrice;

    public function newArticle()
    {
        usleep(200 * 1000);
        $this->enableFinalizeOrderButton = false;
        $this->enableActions = false;
        $this->showNewArticleButton = false;
        $this->showArticleSelector = true;
    }
    
    public function getArticleData($orderType)
    {
        if ($this->articleId != null) {
            if ($orderType === 'Buy') {
                $article = Supply::findOrFail($this->articleId);
            }
            if ($orderType === 'Sell') {
                $article = Product::findOrFail($this->articleId);
            }

            $this->articleTitle = $article->title;
            $this->price = $article->price;
            $this->partialPrice = $this->price;
            $this->reset('quantity');
            $this->showQuantityPrice = true;
            $this->showOptions = true;
        } else {
            $this->showQuantityPrice = false;
            $this->showOptions = false;
        }
    }

    public function moreQuantity()
    {
        $this->quantity ++;
        $this->partialPrice = $this->price * $this->quantity;
    }
    public function lessQuantity()
    {
        $this->quantity --;
        $this->partialPrice = $this->price * $this->quantity;
    }
    
    public function addArticle()
    {
        usleep(200 * 1000);
        $this->articleList[] = $this->modelData();  
        $this->setTotalPrice();
        $this->closeArticleForm();
        $this->showArticleList = true;
        $this->showFinalizeOrderButton = true;
    }

    public function editArticle($key)
    {
        $this->articleListKey = $key;
        $article = $this->articleList[$key];

        $this->articleId = $article['id'];
        $this->articleTitle = $article['title'];
        $this->price = $article['price'];
        $this->quantity = $article['quantity'];
        $this->partialPrice = $article['partialPrice'];

        $this->enableFinalizeOrderButton = false;
        $this->enableActions = false;
        $this->showNewArticleButton = false;
        $this->showArticleSelector = true;
        $this->showQuantityPrice = true;
        $this->showUpdateButton = true;
        $this->showOptions = true;
    }

    public function updateArticle()
    {
        usleep(200 * 1000);
        $this->articleList[$this->articleListKey] = $this->modelData();  
        $this->setTotalPrice();
        $this->closeArticleForm();
        $this->showFinalizeOrderButton = true;
    }

    public function deleteArticle($key)
    {
        unset($this->articleList[$key]);
        $this->setTotalPrice();

        if (empty($this->articleList)) {
            $this->showArticleList = false;
            $this->showFinalizeOrderButton = false;       
        }
    }

    public function setTotalPrice()
    {
        $total = 0;
        foreach ($this->articleList as $article) {
            $total += $article['partialPrice'];
        }

        $this->totalArticlesPrice = $total;
    }

    public function closeArticleForm()
    {
        $this->reset([
            'articleId',
            'articleTitle',
            'quantity',
            'price',
            'partialPrice',
            'showNewArticleButton',
            'enableActions',
            'enableFinalizeOrderButton',
            'showArticleSelector',
            'showOptions',
            'showUpdateButton',
            'showQuantityPrice',
        ]);
    }

    public function modelData()
    {
        return [
            'id' => $this->articleId,
            'title' => $this->articleTitle,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'partialPrice' => $this->partialPrice,
        ];
    }

    public function finalizeOrder()
    {
        $this->showActions = false;
        $this->showNewArticleButton = false;
        $this->emitUp('finalizeOrder', $this->articleList, $this->totalArticlesPrice);
        $this->showFinalizeOrderButton = false;
    }

    public function resetArticles()
    {
        $this->reset();
    }

}
