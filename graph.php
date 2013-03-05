<?php

class Vertex {
    public $edges;
    public $index;

    public function __construct() {
        $this->edges = array();
    }

    public function display() {
        echo "{\n\tedges : [\n";
        foreach($this->edges as $edge) {
            echo "\t";
            $edge->display();
        }

        echo "\tindex : {$this->index} \n}\n";
    }
}

class Edge {
    public $vertex_a;
    public $vertex_b;
    public $weight;
    public $id;

    public function __construct() {
        $this->id = uniqid($prefix = "edge", $more_entropy = true);
    }

    public function display() {
        echo "{\n\ta : {$this->vertex_a->index},\n\tb : " .
            "{$this->vertex_b->index},\n\t" .
            "weight : {$this->weight}\n}" . PHP_EOL;
    }

}

class Path {
    public $vertices;
    public $edges;
    public $length;

    public function __construct() {
        $this->vertices = array();
        $this->edges = array();
        $this->length = 0;
    }

    public function add(Edge $edge) {

        if ($edge) {
            $this->edges[] = $edge;
            /**
             * Only add vertex_a the first time so we avoid duplicate
                 vertex entries
             **/
            if (empty($this->vertices)) {
                echo "adding vertex {$edge->vertex_a->index} to path" . PHP_EOL;
                $this->vertices[] = $edge->vertex_a;
            }

            echo "adding vertex {$edge->vertex_b->index} to path" . PHP_EOL;
            $this->vertices[] = $edge->vertex_b;
            $this->length += $edge->weight;
        }
    }

}

/*

Starting with vC = v0

while there are untraversed edges left
    choose shortest untraversed path to neighbor
    length to this neighbor should be calculated from previous known states
    if that neighbor is vN and the length is less than shortest
        shortest = length
    else
        now explore this neighbors neighbors
    
      
if you are at 

V_C = V_0

choose shortest untraversed edge until you reach V_N or here are no more edges to traverse

*/

function setup_vertices($cnt, $max_weight) {

    $vertices = array();

    for ($i = 0; $i < $cnt; $i++) {
        $vertex = new Vertex();
        $vertex->index = $i;
        $vertices[] = $vertex;
    }

    for ($i = 0; $i < $cnt; $i++) {

        for ($j = 0; $j < $cnt; $j++) {

            $vertex_b = rand(0, $cnt - 1);

            if ($vertex_b != $i) {
                $edge = new Edge();
                $edge->vertex_a = $vertices[$i];
                $edge->vertex_b = $vertices[$vertex_b];
                $edge->weight = rand(1, $max_weight);
                $vertices[$i]->edges[] = $edge;
            }
        }
    }

    return $vertices;
}

function store_path(Path $path, array &$paths) {

    $index = "";
    foreach ($path->vertices as $vertex) {
        $index .= $vertex->index;
    }

    echo "storing path as $index" . PHP_EOL;
    $paths[$index] = clone $path;
}

function retrieve_path(array $vertices, array $paths) {

    $index = "";
    foreach($vertices as $vertex) {
        $index .= $vertex->index;
    }

    $path_keys = array_keys($paths);
    foreach($path_keys as $path_key) {
        if ($path_key == $index) {
            echo "retrieving path for $path_key" . PHP_EOL;
            return clone $paths[$path_key];
        }
    }

    echo "did not find an index for '$index'" . PHP_EOL;

    return false;
}

function edge_sort($edge_a, $edge_b) {
    if ($edge_a->weight == $edge_b->weight) {
        return 0;
    }

    return $edge_a->weight > $edge_b->weight ? -1 : 1;
}


$cnt = 10;

$looking_for_vertex = rand(1, $cnt - 1);

$shortest_path = false;
$max_weight = $cnt * 3;
$vertices = setup_vertices($cnt, $max_weight);

//we need to keep track of visited edges
$visited_edges = array();
/**
 * Stack will contain what edges we can get to
 **/
$stack = $vertices[0]->edges;
uasort($stack, 'edge_sort');
/**
 * Current path is the current path we are constructing, it will contain 
 **/
$current_path = new Path();
$current_path->vertices[] = $vertices[0];
/**
 * Paths is an array of all unique paths
 **/
$paths = array();
store_path($current_path, $paths);

while (!empty($stack)) {

    echo "\nWe are looking for $looking_for_vertex" . PHP_EOL;

    $edge_traveled = array_pop($stack);
    //mark this edge as visited
    $visited_edges[] = $edge_traveled->id;


    /**
     * We need to ensure that vertex_a of this edge is the last vertex in our 
     * current path. If not, change the current path to have that
     **/
     if (count($current_path->vertices) > 0 && 

         $current_path->vertices[count($current_path->vertices) - 1]->index 
            != $edge_traveled->vertex_a->index) {

         $tmp_vertices = $current_path->vertices;

         while ($tmp_vertices[count($tmp_vertices) - 1]->index 
             != $edge_traveled->vertex_a->index) {
             array_pop($tmp_vertices);
         }

         $current_path = retrieve_path($tmp_vertices, $paths);
     }


    echo "length of proposed path is " . 
        ($current_path->length + $edge_traveled->weight) . 
        " and shortest is " . 
        ($shortest_path == false ? -1 : $shortest_path->length) . PHP_EOL;

    if (!$shortest_path || $shortest_path->length > ($current_path->length + $edge_traveled->weight)) {

        $current_path->add($edge_traveled);
        $current_vertex = $edge_traveled->vertex_b;

        //store the length of this new path
        store_path($current_path, $paths);


        //have we found the vertex we are looking for??
        if ($current_vertex->index == $looking_for_vertex) {
            echo "found our index!" . PHP_EOL;
            //YES, so check if this is the shortest
            if (!$shortest_path || $current_path->length < $shortest_path->length) {
                echo "its the shortest with {$current_path->length}!" . PHP_EOL;
                $shortest_path = clone $current_path;
            }

            //move back to the previous vertex
            $current_vertex = $edge_traveled->vertex_a;
            $tmp_vertices = $shortest_path->vertices;
            //remove the last vertex
            array_pop($tmp_vertices);
            $current_path = retrieve_path($tmp_vertices, $paths);
            echo "retrieved a path with length " . $current_path->length;
            echo "\n\n\n";
        } 

        //remove any visited edges
        echo "Possible edges" . PHP_EOL;
        $possible_edges = array_filter($current_vertex->edges,
            function($edge) use ($visited_edges) {
                return !in_array($edge->id, $visited_edges);
            });

        //do we have any non visited edges to take
        echo "found " . count($possible_edges) . " possible edges." . PHP_EOL;
        if (count($possible_edges) > 0) {
            uasort($possible_edges, 'edge_sort');
            foreach ($possible_edges as $edge) {
                $stack[] = $edge;
            }
        }
    } 

}

echo "THE SHORTEST PATH IS " . $shortest_path->length;

