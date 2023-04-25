The inspiration for this block came from a feature available in my portfolio built with React. This feature [displays my public repositories](https://sarahjobs.com/code-samples) in GitHub.

The present block does basically the same thing, but instead of React and Javascript, some code was rewritten in PHP. as it became a dynamic block.

# Dynamic Blocks

Creating a Gutenberg Block whose purpose is mainly styling and HTML generation can be done only with Javascript. But for other features like displaying posts, doing remote API calls, etc, it's a better approach to **render the block using PHP** and not JavaScript. 

To achieve that, we added the argument **render_callback** to the **register_block_type** function. That will handle the output of the block, superseding the most common javascript save/frontend function in the /src/index.js file.


# Using Gihut API 

You can easily display a [list with public repositories](https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#list-repositories-for-a-user) of any GitHub user using the GitHub API.
