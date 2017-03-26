<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Posts;

use Illuminate\Http\Request;

class AnalyticsController extends Controller {
	/**
	 * Displays each post tag with the 10 most common words used for each tag.
	 * The number of words is also displayed near each word
	 * The tags and words are case incensitive
	 * 
	 * @return Response
	 */
	public function show_all()
	{
		//$tagsWordsArr will hold the tags->(words->count) array of arrys
		$tagsWordsArr = array();
		//$allPostsWithTags holds all the active posts that contain 
		$allPostsWithTags = Posts::where('active','=',1)->whereNotNull('tags')->get();
		
		//Create a 2D array for tags, words and count
		foreach($allPostsWithTags as $post) {
			//Getting all the tags
			$tags = str_getcsv($post->tags,',');
			
			//Cutting the <p> and </p> from the post and getting only alphabetical words (in uppercass)
			$words = str_word_count(substr(strtoupper($post->body), 3, strlen($post->body) -7),1);
			
			//Going over the tags and creating a tag->(word->count) hash table
			foreach ($tags as $tag) {
				if (!array_key_exists($tag, $tagsWordsArr)) {
					$tagsWordsArr[$tag] = array();
				}
				foreach ($words as $word) {
					//If the word contains less than 2 letters there it is not a word
					if (strlen($word) < 2) continue;
					//Ignoring case
					if (array_key_exists($word, $tagsWordsArr[$tag])) {
						$tagsWordsArr[$tag][$word]++;
					}
					else {
						$tagsWordsArr[$tag][$word] = 1;
					}
				}
			}
		}
		
		//Sorting the results and cutting less frequent words
		foreach (array_keys($tagsWordsArr) as $tag)
		{
			arsort($tagsWordsArr[$tag]);
			array_splice($tagsWordsArr[$tag],10);
		}
		
		return view('analytics.analytics')->with('tagsWordsArr', $tagsWordsArr);
	}
}
