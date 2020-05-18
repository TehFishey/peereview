import React from 'react';
import ImportPanelItem from './ImportPanelItem';

export default class ImportPanelSelect extends React.Component {    
    constructor(props) {
        super(props);
        this.state = {
            importCreatures : this.props.importCreatures,
            checkAllBox: false
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleClose = this.handleClose.bind(this);
        this.handleCheckAll = this.handleCheckAll.bind(this);
    }

    handleSubmit() {
        this.props.onSubmit(this.state.importCreatures);
    }

    handleClose() {
        this.props.onClose();
    }

    handleCheckAll() {
        this.setState({ checkAllBox : !this.state.checkAllBox });
        this.setState({ importCreatures : this.state.importCreatures.map(
            (tuple) => {return [!this.state.checkAllBox, tuple[1]]}
        )});
    }

    onItemCheck(code, checked) {
        this.setState({ importCreatures : this.state.importCreatures.map(
            (tuple) => { return (tuple[1].code === code) ? [checked, tuple[1]] : tuple }
        )});
    }

    createPanelItems() {
        let panelItems = []
        this.state.importCreatures.forEach((tuple, index) => panelItems.push(
            <ImportPanelItem  key = {index}
                code = {tuple[1].code} 
                src={tuple[1].imgsrc}
                checked ={tuple[0]}
                onCheck={(code, checked) => this.onItemCheck(code, checked)}
            />));

        return panelItems;
    }

    render () {
        return (
            <div className="import-panel">
                <label><input type="checkbox" checked={this.state.checkAllBox} onChange={this.handleCheckAll} />(Un)select All</label>
                <div className="import-panel-select">
                    {this.createPanelItems()}
                    <button onClick={this.handleSubmit}>Submit</button>
                    <button onClick={this.handleClose}>Cancel</button>
                </div>
            </div>
        )
    }
}