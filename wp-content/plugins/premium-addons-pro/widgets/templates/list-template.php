<?php 
/**
 * Template: Premium Social Feed List Template
 */
?>
<div class="premium-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{{=it.id}}">
    <div class='premium-content'>
        <a class="premium-feed-element-author-img" href="{{=it.author_link}}" target="_blank">
            <img class="media-object" src="{{=it.author_picture}}">
        </a>
        <div class="media-body">
            <div class="premium-feed-element-meta">
                <i class="fab fa-{{=it.social_network}} premium-social-icon"></i>
                <span class="premium-feed-element-author"><a href="{{=it.author_link}}" target="_blank">{{=it.author_name}}</a></span>
                <span class="muted premium-feed-element-date"><a href="{{=it.link}}" target="_blank">{{=it.time_ago}}</a></span>  
            </div>
            <div class="premium-feed-element-content-wrap">
                <p class="premium-feed-element-text">{{=it.text}} </p>
                <div class="premium-feed-read-more-wrap"><a href="{{=it.link}}" target="_blank" class="premium-feed-element-read-more">{{=it.readMore}}</a></div>
            </div>
        </div>
    </div>
    {{=it.attachment}}
</div>