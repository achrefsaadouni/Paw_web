<?php
/**
 * Created by PhpStorm.
 * User: achref
 * Date: 03/04/2018
 * Time: 23:37
 */

namespace AppBundle\Entity;


class Panier
{
    /**
     * @var array
     */
    protected $articles;
    /**
     * @var double
     */
    protected $total = 0;
    /**
     * @var int
     */
    protected $nombreArticles =0;

    /**
     * @return array
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param array $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }


    public function __construct()
    {
        $this->articles = array();
    }




    /**
     * @param mixed $total
     */
    public function addToTotal($total)
    {
        $this->total += $total;
    }
    /**
     * @param mixed $total
     */
    public function removeToTotal($total)
    {
        $this->total -= $total;
    }
    /**
     * @param Produit $item
     */
    public function addItem($item)
    {
     foreach ($this->articles as $i )
         if ($i->getIdProduit()->getId()==$item->getId())
         {
             if($i->getNbrProduit()+1<=$i->getIdProduit()->getQuantite())
             {
                 $i->setNbrProduit($i->getNbrProduit()+1);
                 $this->addToTotal($item->getPrix());
                 return ;
             }
             return;

         }

         $ligneAchat = new Ligneachat();
         $ligneAchat->setNbrProduit(1);
         $ligneAchat->setIdProduit($item);
         $this->articles[$item->getId()] = $ligneAchat;
         $this->addToTotal($item->getPrix());

    }

    /**
     * @param Produit $aa
     */
    public function removeItem($aa)
    {

        for ($i = 1; $i <= $this->articles[$aa->getId()]->getNbrProduit(); $i++) {
            $this->removeToTotal($aa->getPrix());
        }
            unset($this->articles[$aa->getId()]);

    }
    /**
     * @param Produit $p
     */
    public function plus($p)
    {
        if($this->articles[$p->getId()]->getNbrProduit()+1<=$p->getQuantite())
        {
            $this->articles[$p->getId()]->setNbrProduit($this->articles[$p->getId()]->getNbrProduit()+1);
            $this->addToTotal($p->getPrix());
            return ;
        }
    }
    /**
     * @param Produit $p
     */
    public function minus($p)
    {
        if($this->articles[$p->getId()]->getNbrProduit()+1!=0)
        {
            $this->articles[$p->getId()]->setNbrProduit($this->articles[$p->getId()]->getNbrProduit()-1);
            $this->removeToTotal($p->getPrix());
            return ;
        }
    }

    public function totalItem()
    {
        if (empty($this->articles)) {
            return 0;
        }
        foreach ($this->articles as $a)
        {
            $this->nombreArticles += $a->getNbrProduit() ;

        }

        return $this->nombreArticles;
    }

    public function viderPanier()
    {
        unset($this->articles);
        $this->articles = array();
        $this->nombreArticles = 0;
        $this->total = 0;
    }
}