import { Reducer } from "redux";
import apiFetch from "@wordpress/api-fetch";
import { createReduxStore, register } from "@wordpress/data";

interface icons {
	[set: string]: {
		[icon: string]: object;
	},
}

interface action {
	type: string,
	path: string,
	icons: icons,
}

interface state {
	icons: icons
}

export const defaultState: state = {
	icons: {
		social: {},
		wordpress: {}
	},
};

const actions = {
	setIcons( icons: unknown ) {
		return {
			type: 'SET_ICONS',
			icons,
		};
	},
	getIcons( path: string ) {
		return {
			type: 'GET_ICONS',
			path,
		};
	},
};

const reducer: Reducer<state, any> = ( state: state = defaultState, action: action ) => {
	switch ( action.type ) {
		case 'SET_ICONS': {
			return {
				...state,
				icons: action.icons,
			};
		}
		default: {
			return state;
		}
	}
};

const selectors = {
	getIcons( state: state ) {
		const { icons } = state;
		return icons;
	},
};

const controls: { [key: string]: ( action: any ) => any } = {
	GET_ICONS( action: action ) {
		return apiFetch( { path: action.path } );
	},
};

const resolvers = {
	* getIcons(): Generator<{ type: string; path: string; }> {
		const icons = yield actions.getIcons( '/blockify/v1/icons/' );

		return actions.setIcons( icons );
	},
};

register( createReduxStore( 'blockify/icons', {
	reducer,
	actions,
	selectors,
	controls,
	resolvers,
} ) );
