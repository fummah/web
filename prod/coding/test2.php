<script src="../admin_main/plugins/jquery/jquery.min.js"></script>
<script src="../admin_main/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="jquery-sortable-min.js"></script>
<script>
    function arrayUnique(array) {
        var a = array.concat();
        for(var i=0; i<a.length; ++i) {
            for(var j=i+1; j<a.length; ++j) {
                if(a[i] === a[j])
                    a.splice(j--, 1);
            }
        }

        return a;
    }

    var array1 = ["Vijendra","Singh"];
    var array2 = ["Singh", "Shakya"];
    // Merges both arrays and gets unique items
    var array3 = arrayUnique(array1.concat(array2));
    console.log(array3);
</script>
<style>
    body {font-family: sans-serif;}

    input { font-size: 1em; } /* prevent zoom in mobile */

    ol {
        /* list style is faked with number inputs */
        list-style: none;
        padding: 0;
    }

    li {
        position: relative;
        min-height: 1em;
        cursor: move;
        padding: .5em .5em .5em 2.5em;
        background: #eee;
        border: 1px solid #ccc;
        margin: .25em 0;
        border-radius: .25em;
        max-width: 14em;
    }

    li input {
        /* Move these to visually fake the ol numbers */
        position: absolute;
        width: 1.75em;
        left: .25em;
        top: .25em;
        border: 0;
        text-align: center;
        background: transparent
    }

    li label {
        /* visually hidden offscreen so it still benefits screen readers */
        position: absolute;
        left: -9999px;
    }

    /* sortable plugin styles when dragged */
    .dragged {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
    }

    li.placeholder {
        position: relative;
        background: purple;
    }

</style>

<h1>Accessible, drag & drop, touch-friendly, sortable list</h1>
<p>Mouse and touch: drag and drop to reorder. Keyboard and text-based: change the number input and hit enter or the update button.</p>
<form id="sort-it">
    <ol>
        <li><span class="tct" data-id="item5">This is item #1</span>
            <label for="custom-number-1">New order:</label>
            <input id="custom-number-1" name="custom-number-1" type="number" min="1">
        </li>

        <li><span class="tct" data-id="item6">This is item #2</span>
            <label for="custom-number-2">New order:</label>
            <input id="custom-number-2" name="custom-number-2" type="number" min="1">
        </li>

        <li><span class="tct" data-id="item7">This is item #3</span>
            <label for="custom-number-3">New order:</label>
            <input id="custom-number-3" name="custom-number-3" type="number" min="1">
        </li>

        <li><span class="tct" data-id="item8">This is item #4</span>
            <label for="custom-number-4">New order:</label>
            <input id="custom-number-4" name="custom-number-4" type="number" min="1">
        </li>

        <li><span class="tct" data-id="item9">This is item #5</span>
            <label for="custom-number-5">New order:</label>
            <input id="custom-number-5" name="custom-number-5" type="number" min="1">
        </li>
    </ol>
    <input type="submit" id="manual-sort" name="manual-sort" value="Update">
</form>

<button onclick="sRange()">Test</button>
