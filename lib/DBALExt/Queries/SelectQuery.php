<?PHP
namespace DBALExt\Queries;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

class SelectQuery extends ClausesQuery {
  protected function getClauses() {
    return array(
      'SELECT'      => new Clauses\SelectClause($this->schema),
      'FROM'        => new Clauses\FromClause(),
      'WHERE'       => new Clauses\WhereClause($this->schema, 'AND', 'WHERE'),
      // 'GROUP BY'    => NULL,
      // 'HAVING'      => NULL,
      // 'ORDER BY'    => NULL,
      // 'LIMIT'       => NULL
    );
  }
  
  
  public function select() {
    $args = func_get_args();
    
    $select_clause = new Clauses\SelectClause($this->schema);
    foreach ($args as $arg) {
      $select_clause->addColumn($arg);
    }
    $this->setClause('SELECT', $select_clause);
    
    return $this;
  }
  
  
  public function addSelect($field_format, $type = NULL) {
    $select_clause = $this->getClause('SELECT');
    $select_clause->addColumn($field_format, $type);
    
    return $this;
  }
  
  
  public function from($table_name) {
    $this->getClause('FROM')->setTableName($table_name);
    
    return $this;
  }
  
  
  public function where(\Closure $setup_where) {
    $setup_where($this->getClause('WHERE'));
    
    return $this;
  }
  
  
  public function getConversionTypes() {
    return $this->getClause('SELECT')->getTypes();
  }
}
