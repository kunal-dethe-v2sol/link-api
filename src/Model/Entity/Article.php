<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Article Entity
 *
 * @property string $uuid
 * @property string $user_id
 * @property string $image_serialize
 * @property string $headline
 * @property string $article_text
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property string $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ArticleComment[] $article_comments
 * @property \App\Model\Entity\ArticleLike[] $article_likes
 * @property \App\Model\Entity\ArticleSharewithEmail[] $article_sharewith_email
 * @property \App\Model\Entity\ArticleSharewithGroup[] $article_sharewith_groups
 * @property \App\Model\Entity\ArticleSharewithPerson[] $article_sharewith_people
 */
class Article extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'uuid' => false
    ];
}
