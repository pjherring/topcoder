<?php

class Vertex {
    public $edges;
    public $index;

    public function __construct() {
        $this->edges = array();
    }
}

class Edge {
    public $vertex_a;
    public $vertex_b;
    public $weight;
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

    public function add($vertex, $edge) {
        $vertices[] = $vertex;
        $edges[] = $edge;
        $length += $edge->weight;
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
        $vertices[] = new Vertex();
        $vertices[$i]->edges = array();
        $vertices[$i]->index = $i;

    }

    for ($i = 0; $i < $cnt; $i++) {

        for ($j = 0; $j < $cnt / 2; $j++) {

            $vertex_b = rand(0, $cnt - 1);

            if ($vertex_b != $i) {
                $edge = new Edge();
                $edge->vertex_a = $i;
                $edge->vertext_b = $vertext_b;
                $edge->weight = rand(1, $max_weight);
            }
        }
    }

    return $vertices;
}

$cnt = 10;

$looking_for_vertex = rand(0, $cnt - 1);
$shortest_path = -1;
$max_weight = $cnt * 3;
$vertices = setup_vertices($cnt, $max_weight);

//we need to keep track of visited edges
$visited_edges = array();
/**
 * Stack will contain what vertices we can get to
 **/
$stack = array();
/**
 * Current path is the current path we are constructing, it will contain 
 **/
$current_path = new Path();
/**
 * Paths is an array of all unique paths
 **/
$paths = array();

array_push($stack, $vertices[0);

while (!is_empty($stack)) {

    $current = array_pop($stack);

    //remove any visited edges
    $edges = array_filter($current->get_edges(), function($edge) {
        return !in_array($visited_edges, $edge);
    });

    if (count($edges) > 0) {

        //sort the edges in highest weight to shortest weight
        $edges = uasort($edges, function($edge_a, $edge_b) {
            if ($edge_a->weight == $edge_b->weight) {
                return 0;
            }

            return $edge_a->weight > $edge_b->weight ? -1 : 1;
        });

        //push all unvisited edges on
        foreach ($edges as $edge) {
            array_push($stack, $vertices[$edge->vertext_b]);
        }

        $shortest_edge = $edges[count($edges) - 1];
        $current_path->add($vertices[$shortest_edge->vertext_b], $shortest_edge);
        $visited_edges[] = $shortest_edge;

        if ($shortest_edge->vertext_b == $looking_for_vertex) {
            if ($shortest == -1 || $shortest > $current_path->length) {
                $shortest = $current_path->length;
            }
        } else {
        }
    } else {
        echo "No unvisited edges from vertex {$current->index}" . PHP_EOL;
    }
}
