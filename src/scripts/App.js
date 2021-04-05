import '../styles/App.css';
import Login from './Login.js';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';

function App() {
  return (
    <Router>
      <Switch>
        <Route path="/login" exact component={Login}></Route>
        <Route path="/login" exact component={Login}></Route>
      </Switch>
    </Router>
  );
}

export default App;
