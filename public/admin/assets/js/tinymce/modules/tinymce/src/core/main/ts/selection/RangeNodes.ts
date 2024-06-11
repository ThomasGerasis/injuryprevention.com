/**
 * Copyright (c) Tiny Technologies, Inc. All rights reserved.
 * Licensed under the LGPL or a commercial license.
 * For LGPL see License.txt in the project root for license information.
 * For commercial licenses see https://www.tiny.cloud/
 */

import { Num } from '@ephox/katamari';

import * as NodeType from '../dom/NodeType';

const getSelectedNode = (range: Range): Node => {
  const startContainer = range.startContainer,
    startOffset = range.startOffset;

  if (startContainer.hasChildNodes() && range.endOffset === startOffset + 1) {
    return startContainer.childNodes[startOffset];
  }

  return null;
};

const getNode = (container: Node, offset: number): Node => {
  if (NodeType.isElement(container) && container.hasChildNodes()) {
    const childNodes = container.childNodes;
    const safeOffset = Num.clamp(offset, 0, childNodes.length - 1);
    return childNodes[safeOffset];
  } else {
    return container;
  }
};

/** @deprecated Use getNode instead */
const getNodeUnsafe = (container: Node, offset: number): Node | undefined => {
  // If a negative offset is used on an element then `undefined` should be returned
  if (offset < 0 && NodeType.isElement(container) && container.hasChildNodes()) {
    return undefined;
  } else {
    return getNode(container, offset);
  }
};

export {
  getSelectedNode,
  getNode,
  getNodeUnsafe
};
